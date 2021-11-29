<?php

namespace App\Helpers\Classes;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Throwable;

/**
 * Class FileHandler
 * @package App\Helpers\Classes
 */
class FileHandler
{
    /**
     * @param UploadedFile|null $file
     * @param string|null $dir
     * @param string|null $fileName
     * @return string|null Stored file name, null if uploaded file is null or unable to upload
     */
    public static function storeFile(?UploadedFile $file, ?string $dir = '', ?string $fileName = ''): ?string
    {
        if (!$file) {
            return null;
        }
        $fileName = $fileName ?: md5(time()) . '.' . $file->getClientOriginalExtension();
        if ($dir) {
            $dir = $dir . "/" . date('Y/F');
            if (file_exists($dir)) {
                mkdir($dir, 0777);
            }
        }
        //TODO: add image resizer to store thumbnails
        try {
            $path = Storage::putFileAs(
                $dir, $file, $fileName
            );
        } catch (Throwable $exception) {
            return $exception;
        }

        return $path;
    }

    public static function storeFileContent(array $contents, array $allowContentExtension, string $basePath): array
    {
        if (empty($contents)) {
            return [];
        }

        if (file_exists($basePath)) {
            mkdir($basePath, 0777);
        }
        $uploadedFilePath = [];
        foreach ($contents as $fileUrl) {
            $contentName = Uuid::uuid4() . "-" . substr($fileUrl, strrpos($fileUrl, '/') + 1);
            $fileNameExplode = explode(".", $contentName);

            if (!empty($fileNameExplode) && in_array(end($fileNameExplode), $allowContentExtension)) {
                $content = file_get_contents($fileUrl);
                $contentPath = $basePath . "/" . $contentName;

                if (Storage::put($contentPath, $content)) {
                    $uploadedFilePath[] = $contentPath;
                }
            }
        }
        return $uploadedFilePath;
    }

    /**
     * @param string|array $paths
     * @return bool
     */
    public
    static function deleteFile(string|array $paths): bool
    {
        if (empty($paths)) {
            return false;
        }

        try {
            $existingFilePath = [];
            if (is_array($paths)) {
                foreach ($paths as $path) {
                    if (Storage::exists($path)) {
                        $existingFilePath[] = $path;
                    }
                }
            } else {
                if (Storage::exists($paths)) {
                    $existingFilePath[] = $paths;
                }
            }
            Storage::delete($existingFilePath);
        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
            return false;
        }

        return true;
    }
}
