<?php

namespace App\Http\Transformers;

use App\Services\NPS\Models\NPSResult;
use League\Fractal\TransformerAbstract;

class NPSResultTransformer extends TransformerAbstract
{
    public function transform(NPSResult $NPSResult)
    {
        return [
            'metric' => $NPSResult->metric,
            'total' => $NPSResult->total,
            'entity' => $NPSResult->entity,
            'entityId' => $NPSResult->entityId,
        ];
    }
}
