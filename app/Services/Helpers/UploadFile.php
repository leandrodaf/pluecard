<?php

namespace App\Services\Helpers;

use Illuminate\Support\Facades\Storage;

class UploadFile
{
    private $base64Service;

    public function __construct(Base64Service $base64Service)
    {
        $this->base64Service = $base64Service;
    }

    public function uploadBase64File(string $path, string $fileName, string $base64): string
    {
        $file = $this->base64Service->convertToFile($base64);

        $extension = $this->base64Service->getExtensionByMimeType($file);

        $fullPath = "$path/$fileName.$extension";

        Storage::put($fullPath, $file->getContent());

        return Storage::url($fullPath);
    }
}
