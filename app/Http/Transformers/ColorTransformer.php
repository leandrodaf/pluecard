<?php

namespace App\Http\Transformers;

use App\Models\Card\ColorCard;
use League\Fractal\TransformerAbstract;

class ColorTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'models',
    ];

    public function transform(ColorCard $colorCard)
    {
        return [
            'id' => $colorCard->id,
            'name' => $colorCard->name,
            'matrix' => $colorCard->matrix,
            'created_at' => $colorCard->created_at,
            'updated_at' => $colorCard->updated_at,
            'deleted_at' => $colorCard->deleted_at,
        ];
    }

    public function includeModels(ColorCard $colorCard)
    {
        return $this->collection($colorCard->models, new ModelCardTransformer);
    }
}
