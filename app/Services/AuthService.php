<?php

namespace App\Services;

use Illuminate\Auth\Access\AuthorizationException;

class AuthService
{
    public function login(string $username, string $password): string
    {
        $credentals = ['email' => $username, 'password' => $password];

        $token = auth()->attempt($credentals);

        throw_if(! $token, AuthorizationException::class, null, 401);

        return $token;
    }
}
