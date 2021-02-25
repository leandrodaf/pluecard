<?php

namespace App\Http;

use League\Fractal\TransformerAbstract;

abstract class CardIncludeAbstract extends TransformerAbstract
{
    protected $availableIncludes = [
        'models',
    ];
}
