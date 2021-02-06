<?php

namespace App\Events;

use App\Models\Payment\Payment;
use App\Models\User;

class PaymentCreated extends Event
{
    public $user;

    public $payment;

    public function __construct(User $user, Payment $payment)
    {
        $this->user = $user;

        $this->payment = $payment;
    }
}
