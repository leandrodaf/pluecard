<?php

namespace App\Services;

use App\Events\CreateResetPassword;
use App\Events\UserCreate;
use App\Exceptions\ValidatorException;
use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;

class AccountService
{
    private $user;

    private $resetPassword;

    public function __construct(User $user, ResetPassword $resetPassword)
    {
        $this->user = $user;

        $this->resetPassword = $resetPassword;
    }

    public function create(array $attributes): User
    {
        $user = $this->user->create($attributes);

        event(new UserCreate($user));

        return $user;
    }

    public function resetPasswordUpdate(User $user, array $attributes): void
    {
        $hash = $this->resetPassword->where('user_id', $user->id)
            ->where('enable', true)
            ->where('hash', $attributes['hash'])
            ->whereDate('validated_at', Carbon::today())
            ->firstOrFail();

        $hash->update(['enable' => false]);

        $user->update(['password' => $attributes['password']]);
    }

    public function resetPassword(User $user): void
    {
        $quantity = $this->resetPassword->where('user_id', $user->id)->whereDate('created_at', Carbon::today())->count();

        throw_if($quantity >= 5, ValidatorException::class, ['Password reset attempts exhausted.']);

        $this->resetPassword->where('user_id', $user->id)->update(['enable' => false]);

        $resetPasswords = $user->resetPasswords()->create([
            'hash' => mt_rand(1000000000, 9999999999),
            'validated_at' => Carbon::today(),
            'enable' => true,
        ]);

        event(new CreateResetPassword($user, $resetPasswords));
    }

    public function forgotPassword(string $email): void
    {
        $user = $this->user->where('email', $email)->firstOrFail();

        $this->resetPassword($user);
    }

    public function forgotPasswordConfirmation(string $hash, string $password): void
    {
        $resetPassword = $this->resetPassword->where('hash', $hash)->with('user')->firstOrFail();

        $this->resetPasswordUpdate($resetPassword->user, compact('hash', 'password'));
    }
}
