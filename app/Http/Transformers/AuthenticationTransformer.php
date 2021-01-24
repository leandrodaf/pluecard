<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

class AuthenticationTransformer extends TransformerAbstract
{
    public function transform(string $token)
    {
        return [
            'accessToken' => $token,
            'tokenType' => 'bearer',
            'expiresIn' => auth()->factory()->getTTL() * 60,
        ];
    }
}
