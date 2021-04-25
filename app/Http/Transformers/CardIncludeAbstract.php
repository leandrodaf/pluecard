<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

abstract class CardIncludeAbstract extends TransformerAbstract
{
    protected $availableIncludes = [
        'models',
    ];
}
