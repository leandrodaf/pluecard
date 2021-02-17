<?php

namespace App\Http\Transformers;

use App\Models\Card\DataCard;
use League\Fractal\TransformerAbstract;

class BodyTransformer extends TransformerAbstract
{
    public function transform(DataCard $dataCard)
    {
        return [
            'id' => $dataCard->id,
            'data' => $dataCard->data,
            'created_at' => $dataCard->created_at,
            'updated_at' => $dataCard->updated_at,
            'deleted_at' => $dataCard->deleted_at,
        ];
    }
}
