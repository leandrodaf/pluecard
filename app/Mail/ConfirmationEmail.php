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

    private $user;

    private $confirmationAccount;

    public function __construct(User $user, ConfirmationAccount $confirmationAccount)
    {
        $this->user = $user;

        $this->confirmationAccount = $confirmationAccount;
    }

    public function build(): Mailable
    {
        return $this->view('emails.user.ConfirmationEmail')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'hash' => $this->confirmationAccount->hash,
                'validatedAt' => $this->confirmationAccount->validatedAt->toDateString(),
            ]);
    }
}
