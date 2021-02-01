<?php

namespace App\Services;

use App\Events\CreateConfirmationAccount;
use App\Exceptions\ValidatorException;
use App\Models\ConfirmationAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Throwable;

class AuthService extends Services
{
    private $user;

    private $confirmationAccount;

    /**
     * @param User $user
     * @param ConfirmationAccount $confirmationAccount
     * @return void
     */
    public function __construct(User $user, ConfirmationAccount $confirmationAccount)
    {
        $this->user = $user;

        $this->confirmationAccount = $confirmationAccount;
    }

    /**
     * @param string $username
     * @param string $password
     * @return string
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function login(string $username, string $password): string
    {
        $credentals = ['email' => $username, 'password' => $password];

        $token = auth()->attempt($credentals);

        throw_unless($token, AuthorizationException::class, null, 401);

        throw_unless(auth()->user()->confirmationEmail, ValidatorException::class, ['The user is not active.']);

        return $token;
    }

    /**
     * @param User $user
     * @return void
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function generateHashConfirmation(User $user): void
    {
        throw_if($user->confirmationEmail, ValidatorException::class, ['The user is already active.']);

        $newHash = [
            'hash' => mt_rand(1000000000, 9999999999),
            'validated_at' => Carbon::now()->addDays(1),
        ];

        if ($user->confirmationAccount === null) {
            $confirmationHash = $user->confirmationAccount()->create($newHash);
        } else {
            $confirmationHash = $user->confirmationAccount->fill($newHash);
            $confirmationHash->save();
        }

        event(new CreateConfirmationAccount($user, $confirmationHash));
    }

    /**
     * @param string $hash
     * @return User
     */
    public function hashConfirmation(string $hash): User
    {
        $dateNow = Carbon::now();

        $confirmationAccount = $this->confirmationAccount
            ->where('hash', $hash)
            ->whereDate('validated_at', '>', $dateNow)
            ->with('user')->firstOrFail();

        $confirmationAccount->update(['validated_at' => $dateNow]);

        $user = $confirmationAccount->user;

        $user->update(['confirmationEmail' => true]);

        return $user;
    }

    /**
     * @param string $email
     * @return void
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function refreshHash(string $email): void
    {
        $user = $this->user->where('email', $email)->where('confirmationEmail', '!=', true)->firstOrFail();

        $this->generateHashConfirmation($user);
    }
}
