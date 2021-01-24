<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build(): Mailable
    {
        return $this->view('emails.user.ConfirmationEmail')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'url' => '',
            ]);
    }
}
