<?php

namespace App\Services;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Image;
use Illuminate\Support\Facades\Log;

class ImageRecognitionService
{
    public function __construct()
    {
    }

    public function analyzeImage(string $imagePath): array
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
