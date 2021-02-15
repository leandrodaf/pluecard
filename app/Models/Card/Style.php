<?php

namespace App\Models\Card;

use App\Models\Model;
use App\Services\Helpers\UploadFile;
use Illuminate\Database\Eloquent\SoftDeletes;

class Style extends Model
{
    use SoftDeletes;

    protected $table = 'styles';

    protected $fillable = [
        'name',
        'background',
    ];

    public function setBackgroundAttribute(string $value): void
    {
        $uploadFile = app(UploadFile::class);

        $fileName = rand(10, 100).time();

        $fileUrl = $uploadFile->uploadBase64File('cards/styles/background', $fileName, $value);

        $this->attributes['background'] = $fileUrl;
    }
}
