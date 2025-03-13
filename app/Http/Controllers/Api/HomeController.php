<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Google\ApiCore\ValidationException;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature\Type;
use Illuminate\Http\Request;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Image;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller

{
    public function sendWord(Request $request)
    {
        $words = ['dog', 'cat', 'car', 'book', 'mobile'];
        $word = $words[array_rand($words)];

        session(['expected_word' => $word]);

        return response()->json(['word' => $word]);
    }


    /**
     * @throws ValidationException
     */
    public function verifyImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120' //
        ]);

        $image = $request->file('image');
        $path = $image->store('uploads', 'public');

        $labels = $this->detectLabels(storage_path("app/public/" . $path));

        $expectedWord = session('expected_word', '');

        if (in_array(strtolower($expectedWord), array_map('strtolower', $labels))) {
            return response()->json(['message' => 'Correct! 🎉', 'labels' => $labels]);
        } else {
            return response()->json(['message' => 'Try again!', 'labels' => $labels], 400);
        }
    }

    /**
     * @throws ValidationException
     */
    private function detectLabels($imagePath)
    {
        $imageAnnotator = new ImageAnnotatorClient(['credentials' => public_path('lessons-453312-572888220f8d.json')]);
        $image = file_get_contents($imagePath);

        $response = $imageAnnotator->labelDetection($image);
        $labels = $response->getLabelAnnotations();

        $results = [];
        if ($labels) {
            foreach ($labels as $label) {
                $results[] = $label->getDescription();
            }
        }

        return $results;
    }


    /**
     * @throws ValidationException
     */
//    function analyzeImage($imagePath)
//    {
//        $client = new ImageAnnotatorClient();
//
//
//        $image = file_get_contents($imagePath);
//
//
//        $imageObject = (new Image())->setContent($image);
//
//
//        $request = (new AnnotateImageRequest())
//            ->setImage($imageObject)
//            ->setFeatures([(new Feature())->setType(Feature::TYPE_LABEL_DETECTION)]);
//
//
//        $response = $client->batchAnnotateImages([$request]);
//
//
//        $labels = $response->getResponses()[0]->getLabelAnnotations();
//
//        $results = [];
//        if ($labels) {
//            foreach ($labels as $label) {
//                $results[] = $label->getDescription();
//            }
//        }
//
//        $client->close();
//
//        return $results;
//    }

    private function analyzeImage(string $imagePath): array
    {
        Log::info("Starting analyzeImage for: " . $imagePath);

        try {
            $credentialsPath = public_path('googleAi/lessons-453312-572888220f8d.json');

            Log::info("Credentials path: " . $credentialsPath);

            if (!file_exists($credentialsPath)) {
                Log::error('Credentials file not found at: ' . $credentialsPath);
                return [];
            }

            $imageAnnotator = new ImageAnnotatorClient([
                'credentials' => json_decode(file_get_contents($credentialsPath), true)
            ]);

            Log::info("ImageAnnotatorClient created successfully.");

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

    public function test_image(Request $request)
    {
        $imagePath = public_path('images/sample.jpg');

        if (!file_exists($imagePath)) {
            return "Error: Image file not found at " . $imagePath;
        }

        $labels = $this->analyzeImage($imagePath);
        dd($labels);
    }


}
