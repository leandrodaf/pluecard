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

    public $subject = 'Solicitação de reset de senha | PlueCard';

    public function __construct(
        private User $user,
        private ResetPassword $resetPassword
    ) {
    }

    public function build(): Mailable
    {
        return $this->view('emails.user.ResetPasswordEmail')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'hash' => $this->resetPassword->hash,
                'validated_at' => $this->resetPassword->validated_at->toDateString(),
            ]);
    }
}
