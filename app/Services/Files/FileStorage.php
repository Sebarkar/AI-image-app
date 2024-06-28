<?php

namespace App\Services\Files;

use App\Models\Files;
use App\Services\Image\ImageProcessor;
use App\Services\Image\ImageTransformer;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\EncodedImage;
use Intervention\Image\Image;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class FileStorage
{
    public static function storeZip($files, $target, $targetType = 'tasks')
    {
        $tmpDir = (new TemporaryDirectory())
            ->deleteWhenDestroyed()
            ->make();

        $tmpPath = $tmpDir->path($target->id . $targetType . '.zip');

        $zip = new \ZipArchive();

        if ($zip->open($tmpPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($files as $key => $file) {
                $fileContent = file_get_contents($file->getRealPath());
                $zip->addFromString($key . 'uploaded_file.' . $file->getClientOriginalExtension(), $fileContent);
            }
            $zip->close();
        } else {
            return response()->json([
                'message' => 'Failed to create zip file',
            ], 400);
        }

        $file = new File($tmpPath);

        $filename = hash('sha256', $target->id . $targetType . rand(0, 102200));

        Storage::disk('archives')->putFileAs('/' . $target->user_id . '/', $file, $filename . '.zip');
        $url = Storage::disk('archives')->url('/' . $target->user_id . '/' . $filename . '.zip');

        Files::create(
            [
                'target_id' => $target->id,
                'target' => $targetType,
                'filename' => $filename . '.zip',
                'storage_path' => '/' . $target->user_id . '/' . $filename . '.zip',
                'storage' => 'archives',
                'extension' => 'zip',
            ]
        );

        return $url;
    }

    public static function storeImageFromAiResponse($file, $task)
    {
        if (is_string($file)) {
            $file = Http::get($file)->body();
            $filename = hash('sha256', $task->id . rand(0, 102200));
            $extension = 'jpg';
        } else {
            $extension = $file->getExtension();
            $filename = $file->getClientOriginalName();
        }

        $filename = $filename . '.' . $extension;


        $storagePath = '/' . $task->user_id . '/' . $task->id . $filename;
        $url = Storage::disk('images')->put($storagePath, $file);

        Files::create(
            [
                'target_id' => $task->id,
                'target' => 'tasks',
                'filename' => $filename,
                'storage_path' => $storagePath,
                'storage' => 'images',
                'extension' => $extension,
            ]
        );

        return $url;
    }

    public static function storeFile(UploadedFile $file, $target, $targetType = 'tasks', $disk = 'images')
    {
        //Check file is exist in DB (Do not overload storage by duplicates)
        if ($oldFile = FileStorage::getSameFileModelFromDb($targetType, $file)) {
            return $oldFile;
        };

        $extension = $file->getExtension() ? $file->getExtension() : 'jpg';

        $filename = hash('sha256', $target->id . rand(0, 102200));

        $filename = $filename . '.' . $extension;

        $fullPath = ($target->user_id ? '/' . $target->user_id . '/' : '') . $target->id . $filename;

        if (Storage::disk($disk)->put($fullPath, file_get_contents($file))) {
            return Files::create(
                [
                    'target_id' => $target->id,
                    'target' => $targetType,
                    'filename' => $filename,
                    'storage_path' => $fullPath,
                    'hash' => hash_file('sha256', $file->getRealPath()),
                    'storage' => $disk,
                    'extension' => $extension,
                ]
            );
        } else {
            return null;
        }
    }

    public static function storeImages($files, $target, $targetType = 'tasks'): array
    {
        $dbFiles = [];
        foreach ($files as $file) {
            $dbFiles[] = self::storeFile($file, $target, $targetType);
        }

        return $dbFiles;
    }

    public static function buildTempPath($extension)
    {
        $tmpDir = (new TemporaryDirectory())
            ->deleteWhenDestroyed()
            ->make();
        return $tmpDir->path('file.' . $extension);
    }

    public static function getExtensionFromUrl($url)
    {
        $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
        return $pathInfo['extension'] ?? 'any'; // Default to empty if not found
    }

    public static function getTempFileFromContent($fileContent, $extension)
    {
        // Save the content to a temporary file
        $tmpPath = self::buildTempPath($extension);
        file_put_contents($tmpPath, $fileContent);

        // Optionally, you can create an UploadedFile object if you need to simulate file upload
        return new UploadedFile(
            $tmpPath,
            'file.' . $extension,
            $extension, // Determine MIME type
            null, // Error code
            true // Test mode to skip some validation
        );
    }

    public static function downloadFromLink($url)
    {
        $fileContent = Http::get($url)->body();
        $extension = self::getExtensionFromUrl($url);

        return self::getTempFileFromContent($fileContent, $extension);
    }

    public static function getSameFileModelFromDb($targetType, UploadedFile $file)
    {
        return Files::where('target', $targetType)->where('hash', hash_file('sha256', $file->getRealPath()))->first();
    }
}
