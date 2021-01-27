<?php

namespace App\Http\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AuthenticationTransformer extends TransformerAbstract
{
    public function transform(string $token)
    {
        return [
            'accessToken' => $token,
            'tokenType' => 'bearer',
            'expiresIn' => Carbon::now()->addSeconds(auth()->factory()->getTTL() * 60),
        ];
    }
}
