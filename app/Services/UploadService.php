<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    public function __construct()
    {
    }
    public function uploadImages(Request $request, string $fileKey, string $directory): ?array
    {
        if ($request->hasFile($fileKey)) {
            $images = [];

            foreach ($request->file($fileKey) as $image) {
                $path = $image->store($directory, 'public');
                $images[] = $path;
            }

            return $images;
        }

        return null;
    }

    public function uploadFiles(Request $request, string $fileKey, string $directory): ?array
    {
        if ($request->hasFile($fileKey)) {
            $files = [];
            foreach ($request->file($fileKey) as $file) {
                $path = $file->store($directory, 'public');
                $files[] = $path;
            }
            return $files;
        }
        return null;
    }

    public function uploadImage(Request $request, string $fileKey, string $directory): ?string
    {
        if ($request->hasFile($fileKey)) {
            $image = $request->file($fileKey);
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $path =  Storage::disk('public')->putFileAs($directory,$image,  $imageName);
            return $path;
        } else {
            return null;
        }
    }

    public function moveImages(array $uploadedFiles, $destinationFolder)
    {
        $savedFiles = [];

        foreach ($uploadedFiles as $fileName) {
            $sourcePath = storage_path('tmp/uploads/' . $fileName);
            if (file_exists($sourcePath)) {
                $newFilePath =  Storage::disk('public')->putFileAs($destinationFolder, $sourcePath, $fileName);
                if($newFilePath){
                    $savedFiles[] =  $destinationFolder . '/' . $fileName;
                    unlink($sourcePath);
                }
            }
        }

        return $savedFiles;
    }
}
