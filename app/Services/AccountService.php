<?php

namespace App\Services;

use App\Events\CreateResetPassword;
use App\Events\UserCreate;
use App\Exceptions\ValidatorException;
use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Throwable;

class AccountService
{
    public function __construct(
        private User $user,
        private ResetPassword $resetPassword
    ) {
    }

    /**
     * @param array $attributes
     * @return User
     * @throws BindingResolutionException
     */
    public function create(array $attributes): User
    {
        $user = $this->user->create($attributes);

        event(new UserCreate($user));

        return $user;
    }

    /**
     * @param User $user
     * @param array $attributes
     * @return void
     * @throws InvalidFormatException
     * @throws MassAssignmentException
     */
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

    /**
     * @param User $user
     * @return void
     * @throws InvalidFormatException
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function resetPassword(User $user): void
    {
        $quantity = $this->resetPassword
            ->where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        throw_if(
            $quantity >= 5,
            ValidatorException::class,
            ['Password reset attempts exhausted.']
        );

        $this->resetPassword->where('user_id', $user->id)->update(['enable' => false]);

        $reset_passwords = $user->resetPasswords()->create([
            'hash' => mt_rand(1000000000, 9999999999),
            'validated_at' => Carbon::today(),
            'enable' => true,
        ]);

        event(new CreateResetPassword($user, $reset_passwords));
    }

    /**
     * @param string $email
     * @return void
     * @throws InvalidFormatException
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function forgotPassword(string $email): void
    {
        $user = $this->user->where('email', $email)->firstOrFail();

        $this->resetPassword($user);
    }

    /**
     * @param string $hash
     * @param string $password
     * @return void
     * @throws InvalidFormatException
     * @throws MassAssignmentException
     */
    public function forgotPasswordConfirmation(string $hash, string $password): void
    {
        $resetPassword = $this->resetPassword->where('hash', $hash)->with('user')->firstOrFail();

        $this->resetPasswordUpdate($resetPassword->user, compact('hash', 'password'));
    }
}
