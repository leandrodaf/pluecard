<?php

namespace App\Models;

use App\Services\Helpers\UploadFile;

class ModelStyle extends Model
{
    protected $table = 'model_styles';

    protected $fillable = [
        'name',
        'background',
    ];

    public function setBackgroundAttribute(string $value): void
    {
        $uploadFile = app(UploadFile::class);

        $fileName = rand(10, 100).time();

        $fileUrl = $uploadFile->uploadBase64File('/models/styles/background', $fileName, $value);

        $this->attributes['background'] = $fileUrl;
    }
}
