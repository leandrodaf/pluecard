<?php

namespace App\Models\Card;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataCard extends Model
{
    use SoftDeletes;

    protected $table = 'data_cards';

    protected $fillable = [
        'data',
    ];

    public function dataable(): MorphTo
    {
        return $this->morphTo();
    }
}
