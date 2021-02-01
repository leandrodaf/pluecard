<?php

namespace App\Models;

class ModelStyle extends Model
{
    protected $table = 'modelStyles';

    protected $fillable = [
        'name',
        'background',
        'enable',
    ];
}
