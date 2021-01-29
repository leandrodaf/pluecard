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
        $hash = $this->resetPassword->where('userId', $user->id)
            ->where('enable', true)
            ->where('hash', $attributes['hash'])
            ->whereDate('validatedAt', Carbon::today())
            ->firstOrFail();

        $hash->update(['enable' => false]);

        $user->update(['password' => $attributes['password']]);
    }

    public function resetPassword(User $user): void
    {
        $quantity = $this->resetPassword->where('userId', $user->id)->whereDate('created_at', Carbon::today())->count();

        throw_if($quantity >= 5, ValidatorException::class, ['Password reset attempts exhausted.']);

        $this->resetPassword->where('userId', $user->id)->update(['enable' => false]);

        $resetPasswords = $user->resetPasswords()->create([
            'hash' => mt_rand(1000000000, 9999999999),
            'validatedAt' => Carbon::today(),
            'enable' => true,
        ]);

        event(new CreateResetPassword($user, $resetPasswords));
    }
}
