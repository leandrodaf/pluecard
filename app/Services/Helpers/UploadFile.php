<?php

namespace App\Services\Helpers;

use Illuminate\Support\Facades\Storage;

trait UploadFile
{
    public function uploadBase64File(string $path, string $base64, string $fileName = null): string
    {
        if (is_null($fileName)) {
            $fileName = rand(10, 100).time();
        }

        $base64Service = app(Base64Service::class);

        $file = $base64Service->convertToFile($base64);

        $extension = $base64Service->getExtensionByMimeType($file);

        $fullPath = "$path/$fileName.$extension";

        Storage::put($fullPath, $file->getContent());

        return Storage::url($fullPath);
    }
}
