<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function index(User $user): bool
    {
        return $user->isAdmin();
    }

    public function adminOrSimpleUser(User $user, User $modelUser): bool
    {
        return $user->isAdmin() || $user->id === $modelUser->id;
    }
}
