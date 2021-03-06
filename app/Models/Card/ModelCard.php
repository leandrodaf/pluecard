<?php

namespace App\Models\Card;

use App\Models\FullTextSearch;
use App\Models\Model;
use App\Services\Helpers\UploadFile;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelCard extends Model
{
    use SoftDeletes, FullTextSearch, UploadFile;

    protected $table = 'models_cards';

    protected $fillable = [
        'name',
        'background',
    ];

    protected $searchable = [
        'name',
    ];

    /**
     * Automatic conversion from base64 to attribute 'background' to url.
     * @param string $value
     * @return void
     * @throws BindingResolutionException
     */
    public function setBackgroundAttribute(string $value): void
    {
        $fileUrl = $this->uploadBase64File('cards/models/background', $value);

        $this->attributes['background'] = $fileUrl;
    }

    public function styles(): BelongsToMany
    {
        return $this->belongsToMany(StyleCard::class, 'model_card_styles_cards', 'model_id', 'style_id');
    }

    public function styleButtons(): BelongsToMany
    {
        return $this->belongsToMany(StyleButtonCard::class, 'model_card_style_buttons', 'model_id', 'style_buttons_id');
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(ColorCard::class, 'model_card_colors_cards', 'model_id', 'color_id');
    }

    public function bodys(): MorphMany
    {
        return $this->morphMany(DataCard::class, 'dataable');
    }
}
