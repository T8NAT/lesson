<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ControllerHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\GameResource;
use App\Http\Resources\ImagesResource;
use App\Models\Category;
use App\Models\Game;
use App\Services\UploadService;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function index(){
        $games = Game::query()->latest()->get();
        $games_data = GameResource::collection($games);
        return ControllerHelper::generateResponseApi(true,'تم جلب كافة الالعاب بنجاح',$games_data,200);
    }


    public function checkGame(Request $request)
    {

//        $game = null;
//
//        if ($request->has('category_id')) {
//            $category = Category::with('games',function ($q) use ($categoryId){
//                $q->where('category_id',$categoryId);
//            })->find($request->game_id);
//
//        } elseif ($request->has('type')) {
//            $game = Game::whereHas('type', function ($query) use ($request) {
//                $query->where('name', $request->type);
//            })->first();
//
//        }
//        if ($category == 1){
//            if ($request->type == 'كلمات') {
//                if ($game->category->words->isEmpty()) {
//                return response()->json(['message' => 'لا توجد كلمات متاحة لهذه اللعبة.'], 404);
//                }
//
//                $allWords = collect($game->category->words)->pluck('words')->flatten();
//                $randomWord = $allWords->random();
//
//                return ControllerHelper::generateResponseApi(true,'ابحث من حولك عن',['game'=>$game->name,'word'=>$randomWord,'category'=>$game->category->name],200);
//            }
//        }

        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return ControllerHelper::generateResponseApi(false, 'خطأ في البيانات المدخلة', $validator->errors(), 422);
        }

        $game_id = $request->input('game_id');
        $category_id = $request->input('category_id');

        $game = Game::with('type', 'categories')
            ->where('id', $game_id)
            ->whereHas('categories', function ($query) use ($category_id) {
                $query->where('categories.id', $category_id);
            })->first();

        if (!$game) {
            return ControllerHelper::generateResponseApi(false, 'اللعبة غير موجودة في هذا القسم', null, 404);
        }

        $gameType = $game->type->name;

        switch ($gameType) {
            case 'كلمات':
                $category = $game->categories()->where('categories.id', $category_id)->first();

                if (!$category || !$category->words) {
                    return ControllerHelper::generateResponseApi(false, 'لا توجد كلمات متاحة لهذه اللعبة في هذا القسم.',
                        null, 404);
                }

                $randomWord = collect($category->words)->pluck('word')->flatten()->random();
                $data = ['game' => $game->name, 'word' => $randomWord, 'category' => $category->name, 'type'=>$gameType];

                $student = Cache::get('student', []);

                if (Auth::guard('student')->check()) {
                    $studentId = Auth::guard('student')->id();
                    $student[] = [
                        'student_id' => $studentId,
                        'randomWord' => $randomWord,
                        ];
                    Cache::put('student', $student, now()->addHours(2));
                }
                return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة البحث عن الاسماء بنجاح', $data, 200);
                break;

//                case 'صورة وكلمات' :
//                    $category = $game->categories()->where('categories.id', $category_id)->first();
//                    if (!$category || !$category->words) {
//                        return ControllerHelper::generateResponseApi(false, 'لا توجد كلمات متاحة لهذه اللعبة في هذا القسم.',
//                            null, 404);
//                    }
//
//                    $randomWords = collect($category->words)->flatten()->random();
//                    $data = ['game' => GameResource::make($game), 'words' => $randomWords,];
//
//                    if (Auth::guard('student')->check()) {
//                        $studentId = Auth::guard('student')->id();
//                        $students[] = [
//                            'student_id' => $studentId,
//                            'correctWord' => '',
//                        ];
//                        Cache::put('students', $students, now()->addHours(2));
//                    }
//                    return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة الكلمات بنجاح', $data, 200);
//                    break;

            case 'صورة وكلمات':
                $category = $game->categories()->where('categories.id', $category_id)->first();

                if (!$category || !$category->words) {
                    return ControllerHelper::generateResponseApi(false, 'لا توجد كلمات متاحة لهذه اللعبة في هذا القسم.', null, 404);
                }

                $allWords = $category->words->pluck('word')->flatten();

                $wordCount = $allWords->count();

                if ($wordCount < 4) {
                    return ControllerHelper::generateResponseApi(false, 'لا يوجد ما يكفي من الكلمات في هذه الفئة للعب هذه اللعبة. يجب أن يكون هناك 4 كلمات على الأقل.', null, 400);
                }

                $correctWord = $allWords->random();

                $incorrectWords = collect([]);
                while ($incorrectWords->count() < 3) {
                    $randomWord = $allWords->random();
                    if ($randomWord !== $correctWord && !$incorrectWords->contains($randomWord)) {
                        $incorrectWords->push($randomWord);
                    }
                }

                $words = collect([$correctWord])->merge($incorrectWords)->shuffle();

//                $wordRecord = $category->words()->whereJsonContains('words', $correctWord)->first();

//                $image = $wordRecord ? $wordRecord->image : null;

                $data = [
                    'game' => $game->name,
                    'image' => ImagesResource::collection($game->images),
                    'words' => $words,
                    'correct_word' => $correctWord,
                ];

                if (Auth::guard('student')->check()) {
                    $studentId = Auth::guard('student')->id();
                    Cache::put('correct_word_' . $studentId, $correctWord, now()->addHours(2));
                }

                return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة الكلمات بنجاح', $data, 200);
                break;


            case 'صوت':
                        break;

                        default:
                            return ControllerHelper::generateResponseApi(false, 'نوع اللعبة غير مدعوم', null, 400);
        }

    }

    public function checkImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageFile = $request->file('image');

        $imagePath = Storage::disk('public')->put('temp_images', $imageFile);
        $fullImagePath = storage_path('app/public/' . $imagePath);


        $studentId = Auth::guard('student')->id();

        $student = collect(Cache::get('student', []))
            ->firstWhere('student_id', $studentId);
//        dd($student);

        if ($student && isset($student['randomWord'])) {
            $randomWord = $student['randomWord'];
        } else {
            Storage::disk('public')->delete($imagePath);
            return ControllerHelper::generateResponseApi(false, 'لم يتم العثور على الكلمة الخاصة بالطالب في الجلسة.', null, 400);
        }

        $labels = $this->analyzeImage($fullImagePath);

        Storage::disk('public')->delete($imagePath);

        $isMatch = false;
        foreach ($labels as $label) {
            if (strtolower($label['description']) == strtolower($randomWord)) {
                $isMatch = true;
                break;
            }
        }

        if ($isMatch) {
            return ControllerHelper::generateResponseApi(true, 'أحسنت! الصورة تطابق الكلمة.', ['match' => true], 200);
        } else {
            return ControllerHelper::generateResponseApi(false, 'للأسف! الصورة لا تطابق الكلمة.', ['match' => false, 'labels' => $labels], 400);
        }
    }
    private function analyzeImage(string $imagePath): array
    {
        Log::info("Starting analyzeImage for: " . $imagePath);

        try {
            $credentialsPath = public_path('googleAi/lessons-453312-572888220f8d.json');

            Log::info("Credentials path: " . $credentialsPath); // Log credentials path

            if (!file_exists($credentialsPath)) {
                Log::error('Credentials file not found at: ' . $credentialsPath);
                return [];
            }

            $imageAnnotator = new ImageAnnotatorClient([
                'credentials' => json_decode(file_get_contents($credentialsPath), true)
            ]);

            Log::info("ImageAnnotatorClient created successfully."); // Log successful client creation

            $imageContent = file_get_contents($imagePath);

            if ($imageContent === false) {
                Log::error("Could not read image file: " . $imagePath);
                return [];
            }

            Log::info("Image content length: " . strlen($imageContent));

            $image = new Image();
            $image->setContent($imageContent);

            $feature = new Feature();
            $feature->setType(Type::LABEL_DETECTION);

            $annotateImageRequest = new AnnotateImageRequest();
            $annotateImageRequest->setImage($image);
            $annotateImageRequest->setFeatures([$feature]);

            $batchAnnotateImagesRequest = new BatchAnnotateImagesRequest();
            $batchAnnotateImagesRequest->setRequests([$annotateImageRequest]);

            Log::info("Sending request to Vision API...");

            $responses = $imageAnnotator->batchAnnotateImages($batchAnnotateImagesRequest);
            $response = $responses->getResponses()[0];

            Log::info("Received response from Vision API.");

            $labels = $response->getLabelAnnotations();

            Log::info("Number of labels found: " . count($labels));

            $imageAnnotator->close();

            $labelData = [];
            if ($labels) {
                foreach ($labels as $label) {
                    $labelData[] = [
                        'description' => $label->getDescription(),
                        'score' => $label->getScore()
                    ];
                }
            }

            Log::info("Returning label data.");
            return $labelData;

        } catch (\Exception $e) {
            Log::error('Cloud Vision API Error: ' . $e->getMessage());
            return [];
        }
    }




}
