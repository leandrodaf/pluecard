<?php

namespace App\Listeners;

use App\Events\CreateConfirmationAccount;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;

class EmailConfirmation
{
    public function handle(CreateConfirmationAccount $event)
    {
        Mail::to($event->user->email)->send(new ConfirmationEmail($event->user, $event->confirmationAccount));
    }
}
