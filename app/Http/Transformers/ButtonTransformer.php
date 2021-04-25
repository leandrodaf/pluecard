<?php

namespace App\Http\Transformers;

use App\Models\Card\ButtonCard;
use League\Fractal\TransformerAbstract;

class ButtonTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'style',
    ];

    public function transform(ButtonCard $button)
    {
        return [
            'id' => $button->id,
            'name' => $button->name,
            'background' => $button->background,
            'created_at' => $button->created_at,
            'updated_at' => $button->updated_at,
            'deleted_at' => $button->deleted_at,
        ];
    }

    public function includeStyle(ButtonCard $button)
    {
        return $this->item($button->styleButton, new StyleButtonsTransformer);
    }
}
