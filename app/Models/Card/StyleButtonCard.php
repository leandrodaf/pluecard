<?php

namespace App\Models\Card;

use App\Models\Model;
use App\Services\Helpers\UploadFile;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StyleButtonCard extends Model
{
    use SoftDeletes;

    protected $table = 'style_buttons';

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

    public function models(): BelongsToMany
    {
        return $this->belongsToMany(ModelCard::class, 'model_card_styles_cards', 'style_id', 'model_id');
    }
}
