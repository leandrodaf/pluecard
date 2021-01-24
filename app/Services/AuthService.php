<?php

namespace App\Services;

use App\Events\UserCreate;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class AuthService
{
    public function create(array $attributes): User
    {
        $user = User::create($attributes);

        event(new UserCreate($user));

        return $user;
    }

    public function login(string $username, string $password): string
    {
        $credentals = ['email' => $username, 'password' => $password];

        $token = auth()->attempt($credentals);

        throw_if(! $token, AuthorizationException::class, null, 401);

        return $token;
    }
}
