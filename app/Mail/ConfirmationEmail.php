<?php

namespace App\Mail;

use App\Models\ConfirmationAccount;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private User $user,
        private ConfirmationAccount $confirmationAccount
    ) {
    }

    public function build(): Mailable
    {
        return $this->view('emails.user.ConfirmationEmail')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'hash' => $this->confirmationAccount->hash,
                'validated_at' => $this->confirmationAccount->validated_at->toDateString(),
            ]);
    }
}
