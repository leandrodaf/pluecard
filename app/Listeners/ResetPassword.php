<?php

namespace App\Listeners;

use App\Events\CreateResetPassword;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Mail;

class ResetPassword
{
    public function handle(CreateResetPassword $event)
    {
        Mail::to($event->user->email)->send(new ResetPasswordEmail($event->user, $event->resetPassword));
    }
}
