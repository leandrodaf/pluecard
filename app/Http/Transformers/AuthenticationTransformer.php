<?php

namespace App\Http\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AuthenticationTransformer extends TransformerAbstract
{
    public function transform(string $token)
    {
        return [
            'access_token' => $token,
            'tokenType' => 'bearer',
            'expires_in' => Carbon::now()->addSeconds(auth()->factory()->getTTL() * 60),
        ];
    }
}
