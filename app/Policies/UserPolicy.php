<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Policy for validate administrator user.
     *
     * @param User $user
     * @return bool
     */
    public function admin(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Policy for validate admin or simple user.
     *
     * @param User $user
     * @param User $modelUser
     * @return bool
     */
    public function adminOrSimpleUser(User $user, User $modelUser): bool
    {
        return $user->isAdmin() || $user->id === $modelUser->id;
    }
}
