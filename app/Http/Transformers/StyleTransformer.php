<?php

namespace App\Http\Transformers;

use App\Models\Card\StyleCard;
use League\Fractal\TransformerAbstract;

class StyleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'models',
    ];

    public function transform(StyleCard $style)
    {
        return [
            'id' => $style->id,
            'name' => $style->name,
            'background' => $style->background,
            'created_at' => $style->created_at,
            'updated_at' => $style->updated_at,
            'deleted_at' => $style->deleted_at,
        ];
    }

    public function includeModels(StyleCard $style)
    {
        return $this->collection($style->models, new ModelCardTransformer);
    }
}
