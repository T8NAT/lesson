<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ControllerHelper;
use App\Http\Controllers\Controller;
use App\Models\Game;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Illuminate\Http\Request;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TestGameController extends Controller
{
    public function sendWord(Request $request, $gameId)
    {

        $game = Game::with('categories.words')->findOrFail($gameId);

        $allWords = collect();
        foreach ($game->categories as $category) {
            if ($category->words->isNotEmpty()) {
                foreach ($category->words as $word) {
                    $allWords = $allWords->concat($word->words);
                }
            }
        }

        if ($allWords->isEmpty()) {
            return response()->json(['message' => 'لا توجد كلمات متاحة لهذه اللعبة.'], 404);
        }

        $randomWord = $allWords->random();

        session()->put('students', []);
        $students = session()->get('students', []);

        if (Auth::guard('student')->check()) {
            $students[] = [
                'student_id' => Auth::guard('student')->id(),
                'randomWord' => $randomWord,
            ];
            session()->put('students', $students);
            dd($students);

        }
        return ControllerHelper::generateResponseApi(true, 'ابحث من حولك عن', ['game' => $game->name, 'word' => $randomWord], 200);
    }

    public function checkImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'word' => 'required|string',
        ]);

        $imageFile = $request->file('image');
        $word = $request->input('word');


        $imagePath = Storage::disk('public')->put('temp_images', $imageFile);
        $fullImagePath = storage_path('app/public/' . $imagePath);

        $labels = $this->analyzeImage($fullImagePath);

        Storage::disk('public')->delete($imagePath);

        $isMatch = false;
        foreach ($labels as $label) {
            if (strtolower($label['description']) == strtolower($word)) {
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
