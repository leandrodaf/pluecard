<?php

namespace App\Mail;

use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    private $resetPassword;

    public $subject = 'Solicitação de reset de senha | PlueCard';

    public function __construct(User $user, ResetPassword $resetPassword)
    {
        $this->user = $user;

        $this->resetPassword = $resetPassword;
    }

    public function build(): Mailable
    {
        return $this->view('emails.user.ResetPasswordEmail')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'hash' => $this->resetPassword->hash,
                'validatedAt' => $this->resetPassword->validatedAt->toDateString(),
            ]);
    }
}
