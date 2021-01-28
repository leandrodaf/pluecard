<?php

namespace App\Events;

use App\Models\ConfirmationAccount;
use App\Models\User;

class CreateConfirmationAccount extends Event
{
    public $user;

    public $confirmationAccount;

    public function __construct(User $user, ConfirmationAccount $confirmationAccount)
    {
        $this->user = $user;

        $this->confirmationAccount = $confirmationAccount;
    }
}
