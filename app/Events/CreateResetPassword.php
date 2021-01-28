<?php

namespace App\Events;

use App\Models\ResetPassword;
use App\Models\User;

class CreateResetPassword extends Event
{
    public $user;

    public $resetPassword;

    public function __construct(User $user, ResetPassword $resetPassword)
    {
        $this->user = $user;

        $this->resetPassword = $resetPassword;
    }
}
