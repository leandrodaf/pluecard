<?php

namespace App\Models;

use App\Models\Model;


class ModelStyle extends Model
{
    protected $table = 'modelStyles';

    protected $fillable = [
        'name',
        'background',
        'enable'
    ];

    protected $casts = [
        'hash' => 'string',
    ];
}
