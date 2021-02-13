<?php

namespace App\Http\Transformers;

use App\Models\Card\Style;
use League\Fractal\TransformerAbstract;

class StyleTransformer extends TransformerAbstract
{
    public function transform(Style $style)
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
}
