<?php

namespace App\Listeners;

use App\Events\UserCreate;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;

class EmailConfirmation
{
    public function handle(UserCreate $event)
    {
        Mail::to($event->user->email)->send(new ConfirmationEmail($event->user));
    }
}
