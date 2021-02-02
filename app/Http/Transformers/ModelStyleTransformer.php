<?php

namespace App\Http\Transformers;

use App\Models\ModelStyle;
use League\Fractal\TransformerAbstract;

class ModelStyleTransformer extends TransformerAbstract
{
    public function transform(ModelStyle $modelStyle)
    {
        return [
            'id' => $modelStyle->id,
            'name' => $modelStyle->name,
            'background' => $modelStyle->background,
            'created_at' => $modelStyle->created_at,
            'updated_at' => $modelStyle->updated_at,
            'deleted_at' => $modelStyle->deleted_at,
        ];
    }
}
