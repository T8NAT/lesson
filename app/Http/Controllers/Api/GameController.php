<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ControllerHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\GameResource;
use App\Http\Resources\ImagesResource;
use App\Models\Category;
use App\Models\Game;
use App\Models\Word;
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
                    $gameState = [
                        'game_type' => $gameType,
                        'random_word' => $randomWord
                    ];
                    Cache::put('student_game_state_'.$studentId, $gameState, now()->addMinutes(30));
                }
//                dd(Cache::get('student_game_state_'.$studentId));
                return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة البحث عن الاسماء بنجاح', $data, 200);
                break;

            case 'صورة وكلمات':
                $category = $game->categories()->where('categories.id', $category_id)->first();

                if (!$category) {
                    return ControllerHelper::generateResponseApi(false, 'القسم غير مرتبط بهذه اللعبة.', null, 404);
                }

                $possibleCorrectItems = Word::where('category_id', $category_id)
                    ->whereNotNull('image_id')
                    ->with('image')
                    ->get();

                if ($possibleCorrectItems->isEmpty()) {
                    return ControllerHelper::generateResponseApi(false, 'لا توجد كلمات مرتبطة بصور في هذا القسم لبدء اللعبة.', null, 404);
                }

                $correctItem = $possibleCorrectItems->random();
                $correctWord = $correctItem->word;

                if (!$correctItem->image || !$correctItem->image->image) {
                    Log::error("Image relationship or path is missing for Word ID: " . $correctItem->id . " despite image_id being set.");
                    return ControllerHelper::generateResponseApi(false, 'حدث خطأ: لم يتم العثور على ملف الصورة المرتبط بالكلمة الصحيحة.', null, 500);
                }
                $correctImagePath = $correctItem->image->image;


                $allWordsInCategory = Word::where('category_id', $category_id)->pluck('word');

                if ($allWordsInCategory->count() < 4) {
                    return ControllerHelper::generateResponseApi(false, 'لا يوجد عدد كافٍ من الكلمات المختلفة في هذا القسم للعب (مطلوب 4 على الأقل).', null, 400);
                }

                $otherWords = $allWordsInCategory->filter(function ($word) use ($correctWord) {
                    return $word !== $correctWord;
                });

                if ($otherWords->count() < 3) {
                    return ControllerHelper::generateResponseApi(false, 'لا يوجد عدد كافٍ من الكلمات *المختلفة* عن الكلمة الصحيحة في هذا القسم (مطلوب 3).', null, 400);
                }

                $incorrectWords = $otherWords->random(3);

                $words = collect([$correctWord])->merge($incorrectWords)->shuffle();

                $data = [
                    'game' => $game->name,
                    'image_url' => url(Storage::url($correctImagePath)),
                    'words' => $words->values()->all(),
                    'correct_word' => $correctWord,
                ];

                if (Auth::guard('student')->check()) {
                    $studentId = Auth::guard('student')->id();
                    $gameState = [
                        'game_type' => $gameType,
                        'correct_answer' => $correctWord
                    ];
                    Cache::put('student_game_state_' . $studentId, $gameState, now()->addMinutes(30));
                }

                return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة صورة وكلمات بنجاح', $data, 200);
                break;

            case 'صوت':
                $category = $game->categories()->where('categories.id', $category_id)->first();
                if (!$category) {
                    return ControllerHelper::generateResponseApi(false, 'القسم المحدد غير مرتبط بهذه اللعبة.', null, 404);
                }

                $itemsWithAudio = Word::where('category_id', $category_id)
                    ->whereNotNull('audio')
                    ->where('audio', '!=', '')
                    ->get();

                if ($itemsWithAudio->isEmpty()) {
                    return ControllerHelper::generateResponseApi(false, 'لا توجد مفردات مرتبطة بملف صوتي في هذا القسم لبدء اللعبة.', null, 404);
                }

                $correctItem = $itemsWithAudio->random();
                $correctWord = $correctItem->word;
                $correctAudioPath = $correctItem->audio;

                if (empty($correctAudioPath)) {

                    Log::error("Audio path is empty for VocabularyItem ID: " . $correctItem->id . " despite filtering.");
                    return ControllerHelper::generateResponseApi(false, 'حدث خطأ: لم يتم العثور على ملف الصوت المرتبط بالكلمة المختارة.', null, 500);
                }


                if (Auth::guard('student')->check()) {
                    $studentId = Auth::guard('student')->id();
                    $gameState = [
                        'game_type' => $gameType,
                        'correct_answer' => $correctWord
                    ];
                    Cache::put('student_game_state_' . $studentId, $gameState, now()->addMinutes(30));
                    Log::info("Sound Game: Cached correct word '{$correctWord}' for student ID {$studentId}");
                } else {
                    Log::warning("Sound Game: Unauthenticated user attempted to start.");
                     return ControllerHelper::generateResponseApi(false, 'المستخدم غير مسجل للدخول.', null, 401);
                }

//                dd(Cache::get('correct_sound_word_' , $student));
                $data = [
                    'game' => $game->name,
                    'audio_url' => url(Storage::url($correctAudioPath)),
                    'correct_word' => $correctWord,
                ];

                return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة المحادثات بنجاح', $data, 200);
                break;

                default:
                    return ControllerHelper::generateResponseApi(false, 'نوع اللعبة غير مدعوم', null, 400);
        }

    }

    public function checkAnswer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'answer' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return ControllerHelper::generateResponseApi(false, 'الإجابة مطلوبة.', $validator->errors(), 422);
        }

        if (!Auth::guard('student')->check()) {
            return ControllerHelper::generateResponseApi(false, 'المستخدم غير مسجل للدخول.', null, 401);
        }
        $studentId = Auth::guard('student')->id();

        $gameState = Cache::get('student_game_state_' . $studentId);

        if (!$gameState) {
            return ControllerHelper::generateResponseApi(false, 'لم يتم العثور على لعبة نشطة أو انتهت مدة الجلسة.', null, 404);
        }

//        $gameType = $gameState['game_type'];
        $correctAnswer = $gameState['correct_answer'];
        $studentAnswer = $request->input('answer');


        $isCorrect = (strtolower(trim($studentAnswer)) === strtolower(trim($correctAnswer)));

        Cache::forget('student_game_state_' . $studentId);

        if ($isCorrect) {
            /* --- إضافة نقاط للطالب  ---
             $student = Auth::guard('student')->user();
            $student->addPoints(POINTS_FOR_CORRECT_ANSWER);
            return ControllerHelper::generateResponseApi(true, 'إجابة صحيحة! أحسنت.', ['points_awarded' => 10 ], 200);
            */

            return ControllerHelper::generateResponseApi(true, 'إجابة صحيحة! أحسنت.',null ,200);
        } else {
            return ControllerHelper::generateResponseApi(false, 'إجابة خاطئة. حاول مرة أخرى!', ['correct_answer' => $correctAnswer], 422);
        }
    }


    public function checkImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!Auth::guard('student')->check()) {
            return ControllerHelper::generateResponseApi(false, 'المستخدم غير مسجل للدخول.', null, 401);
        }
        $studentId = Auth::guard('student')->id();

        $gameState = Cache::get('student_game_state_' . $studentId);

        if ($gameState['game_type'] !== 'كلمات') {
            return ControllerHelper::generateResponseApi(false, 'لم يتم العثور على لعبة نشطة أو انتهت مدة الجلسة.', null, 404);
        }
//        if ($gameState['game_type'] !== 'كلمات') {
//            Log::warning("Student ID {$studentId} called checkImage for '{$gameState['game_type']}' game.");
//            return ControllerHelper::generateResponseApi(false, 'هذه الإجابة يجب أن تكون نصية وليست صورة.', null, 400);
//        }

        $correctWord = $gameState['random_word'];
//dd($correctWord);
        $imageFile = $request->file('image');
        $imagePath = Storage::disk('public')->put('temp_images', $imageFile);
        $fullImagePath = storage_path('app/public/' . $imagePath);
        $labels = $this->analyzeImage($fullImagePath);
        Storage::disk('public')->delete($imagePath);

        $isMatch = false;
        if (!empty($labels)) {
            foreach ($labels as $label) {
                if (isset($label['description']) && strtolower($label['description']) == strtolower($correctWord)) {
                    $isMatch = true;
                    break;
                }
            }
        }

        Cache::forget('student_game_state_' . $studentId);

        if ($isMatch) {
            return ControllerHelper::generateResponseApi(true, 'أحسنت! الصورة تطابق الكلمة.', ['match' => true], 200);
        } else {
            return ControllerHelper::generateResponseApi(false, 'للأسف! الصورة لا تطابق الكلمة.', ['match' => false,  'labels' => $labels ], 400);
        }
    }
//    public function checkImage(Request $request)
//    {
//        $request->validate([
//            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
//        ]);
//
//        $imageFile = $request->file('image');
//
//        $imagePath = Storage::disk('public')->put('temp_images', $imageFile);
//        $fullImagePath = storage_path('app/public/' . $imagePath);
//
//        $studentId = Auth::guard('student')->id();
//
//        $gameState = Cache::get('student_game_state_' . $studentId);
//
////        dd($gameState);
//
//        if ($gameState && isset($gameState['random_word'])) {
//            $randomWord = $gameState['random_word'];
//        } else {
//            Storage::disk('public')->delete($imagePath);
//            return ControllerHelper::generateResponseApi(false, 'لم يتم العثور على الكلمة الخاصة بالطالب في الجلسة.', null, 400);
//        }
//
//        $labels = $this->analyzeImage($fullImagePath);
//
//        Storage::disk('public')->delete($imagePath);
//
//        $isMatch = false;
//        foreach ($labels as $label) {
//            if (strtolower($label['description']) == strtolower($randomWord)) {
//                $isMatch = true;
//                break;
//            }
//        }
//
//        if ($isMatch) {
//            Cache::forget('student');
//            return ControllerHelper::generateResponseApi(true, 'أحسنت! الصورة تطابق الكلمة.', ['match' => true], 200);
//        } else {
//            return ControllerHelper::generateResponseApi(false, 'للأسف! الصورة لا تطابق الكلمة.', ['match' => false, 'labels' => $labels], 400);
//        }
//    }
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
