<?php

namespace App\Models\Card;

use App\Models\Model;
use App\Services\Helpers\UploadFile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ButtonCard extends Model
{
    use SoftDeletes, UploadFile;

    protected $table = 'buttons_card';

    protected $fillable = [
        'name',
        'background',
        'style_buttons_id',
    ];

    public function setBackgroundAttribute(string $value): void
    {
        $fileUrl = $this->uploadBase64File('cards/buttons/background', $value);

        $this->attributes['background'] = $fileUrl;
    }

    public function styleButton(): BelongsTo
    {
        return $this->belongsTo(StyleButtonCard::class, 'style_buttons_id', 'id');
    }
}
