<?php

namespace App\Services;

use App\Events\CreateConfirmationAccount;
use App\Exceptions\ValidatorException;
use App\Models\ConfirmationAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;

class AuthService extends Services
{
    public function login(string $username, string $password): string
    {
        $credentals = ['email' => $username, 'password' => $password];

        $token = auth()->attempt($credentals);

        throw_unless($token, AuthorizationException::class, null, 401);

        throw_unless(auth()->user()->confirmationEmail, ValidatorException::class, ['The user is not active.']);

        return $token;
    }

    public function generateHashConfirmation(User $user): void
    {
        throw_if($user->confirmationEmail, ValidatorException::class, ['The user is already active.']);

        $newHash = [
            'hash' => mt_rand(1000000000, 9999999999),
            'validatedAt' => Carbon::now()->addDays(1),
        ];

        if ($user->confirmationAccount === null) {
            $confirmationHash = $user->confirmationAccount()->create($newHash);
        } else {
            $confirmationHash = $user->confirmationAccount->fill($newHash);
            $confirmationHash->save();
        }

        event(new CreateConfirmationAccount($user, $confirmationHash));
    }

    public function hashConfirmation(string $hash): User
    {
        $dateNow = Carbon::now();

        $confirmationAccount = ConfirmationAccount::where('hash', $hash)->whereDate('validatedAt', '>', $dateNow)->with('user')->firstOrFail();

        $confirmationAccount->update(['validatedAt' => $dateNow]);

        $user = $confirmationAccount->user;

        $user->update(['confirmationEmail' => true]);

        return $user;
    }

    public function refreshHash(string $email): void
    {
        $user = User::where('email', $email)->where('confirmationEmail', '!=', true)->firstOrFail();

        $this->generateHashConfirmation($user);
    }
}
