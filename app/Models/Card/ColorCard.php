<?php

namespace App\Models\Card;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ColorCard extends Model
{
    use SoftDeletes;

    protected $table = 'colors_cards';

    protected $fillable = [
        'name',
        'matrix',
    ];

    public function models(): BelongsToMany
    {
        return $this->belongsToMany(ModelCard::class, 'model_card_colors_cards', 'color_id', 'model_id');
    }
}
