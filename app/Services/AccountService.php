<?php

namespace App\Services;

use App\Events\UserCreate;
use App\Models\User;

class AccountService
{
    public function create(array $attributes): User
    {
        $user = User::create($attributes);

        event(new UserCreate($user));

        return $user;
    }
}
