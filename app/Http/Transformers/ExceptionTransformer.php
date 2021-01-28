<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

class ExceptionTransformer extends TransformerAbstract
{
    public function transform(array $exception)
    {
        return [
            'code' => $exception['code'] ?? null,
            'status' => $exception['status'] ?? null,
            'message' => $exception['message'] ?? null,
            'errors' => $exception['errors'] ?? null,
            'debug' => $exception['debug'] ?? null,
        ];
    }
}
