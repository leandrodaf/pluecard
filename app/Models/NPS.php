<?php

namespace App\Models;

class NPS extends Model
{
    protected $table = 'nps';

    protected $fillable = [
        'relation',
        'relation_id',
        'rating',
        'user_id',
    ];

    protected $casts = [
        'relation' => 'string',
        'relation_id' => 'integer',
        'rating' => 'integer',
        'user_id' => 'integer',
    ];
}
