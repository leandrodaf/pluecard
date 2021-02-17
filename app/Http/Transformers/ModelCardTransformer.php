<?php

namespace App\Http\Transformers;

use App\Models\Card\ModelCard;
use League\Fractal\TransformerAbstract;

class ModelCardTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'styles', 'body', 'bodys', 'colors',
    ];

    public function transform(ModelCard $modelCard)
    {
        return [
            'id' => $modelCard->id,
            'name' => $modelCard->name,
            'background' => $modelCard->background,
            'created_at' => $modelCard->created_at,
            'updated_at' => $modelCard->updated_at,
            'deleted_at' => $modelCard->deleted_at,
        ];
    }

    public function includeStyles(ModelCard $modelCard)
    {
        return $this->collection($modelCard->styles, new StyleTransformer);
    }

    public function includeBodys(ModelCard $modelCard)
    {
        return $this->collection($modelCard->bodys, new BodyTransformer);
    }

    public function includeBody(ModelCard $modelCard)
    {
        return $this->item($modelCard->bodys->first(), new BodyTransformer);
    }

    public function includeColors(ModelCard $modelCard)
    {
        return $this->collection($modelCard->colors, new ColorModelRelationTransformer);
    }
}
