<?php

namespace App\Services\Files;

use App\Models\Files;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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

    public static function storeImages($files, $target, $targetType = 'tasks'): array
    {
        $dbFiles = [];
        foreach ($files as $file) {
            if (is_string($file)) {
                $file = Http::get($file)->body();
            }

            $extension = $file->getExtension() ? $file->getExtension() : 'jpg';

            $filename = hash('sha256', $target->id . rand(0, 102200));

            $filename = $filename . '.' . $extension;

            $storagePath = '/' . $target->user_id . '/' . $target->id . $filename;

            Storage::disk('images')->put($storagePath, file_get_contents($file));

            $dbFiles[] = Files::create(
                [
                    'target_id' => $target->id,
                    'target' => $targetType,
                    'filename' => $filename,
                    'storage_path' => $storagePath,
                    'storage' => 'images',
                    'extension' => $extension,
                ]
            );
        }

        return $dbFiles;
    }
}
