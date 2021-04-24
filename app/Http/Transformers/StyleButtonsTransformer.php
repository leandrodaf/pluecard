<?php

namespace App\Http\Transformers;

use App\Http\CardIncludeAbstract;
use App\Models\Card\StyleButtonCard;

class StyleButtonsTransformer extends CardIncludeAbstract
{
    protected $availableIncludes = [
        'models', 'buttons',
    ];

    public function transform(StyleButtonCard $styleButton)
    {
        return [
            'id' => $styleButton->id,
            'name' => $styleButton->name,
            'background' => $styleButton->background,
            'created_at' => $styleButton->created_at,
            'updated_at' => $styleButton->updated_at,
            'deleted_at' => $styleButton->deleted_at,
        ];
    }

    public function includeModels(StyleButtonCard $styleButton)
    {
        return $this->collection($styleButton->models, new ModelCardTransformer);
    }

    public function includeButtons(StyleButtonCard $styleButton)
    {
        return $this->collection($styleButton->buttons, new ButtonTransformer);
    }
}
