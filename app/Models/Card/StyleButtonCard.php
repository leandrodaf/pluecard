<?php

namespace App\Models\Card;

use App\Models\Model;
use App\Services\Helpers\UploadFile;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StyleButtonCard extends Model
{
    use SoftDeletes, UploadFile;

    protected $table = 'style_buttons';

    protected $fillable = [
        'name',
        'background',
    ];

    public function setBackgroundAttribute(string $value): void
    {
        $fileUrl = $this->uploadBase64File('cards/styles/background',  $value);

        $this->attributes['background'] = $fileUrl;
    }

    public function models(): BelongsToMany
    {
        return $this->belongsToMany(ModelCard::class, 'model_card_styles_cards', 'style_id', 'model_id');
    }
}
