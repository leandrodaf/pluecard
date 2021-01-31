<?php

use App\Events\CreateResetPassword;
use App\Events\UserCreate;
use App\Exceptions\ValidatorException;
use App\Models\ResetPassword;
use App\Models\User;
use App\Services\AccountService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreatedNewUser()
    {
        $this->expectsEvents(UserCreate::class);

        $data = [
            'name' => 'foo-name',
            'email' => 'fo1o@mail.com',
            'password' => '1234',
            'acceptTerms' => true,
            'newsletter' => true,
            'discountCoupons' => true,
            'confirmationEmail' => true,
        ];

        $this->mockDependence(User::class, function ($user) use ($data) {
            $user->shouldReceive('create')->with($data)->once()->andReturn(new User());

            return $user;
        });

        $this->app->make(AccountService::class)->create($data);
    }

    public function testUpdatePassword()
    {
        Carbon::setTestNow();

        $user = Mockery::mock(User::factory()->make());

        $data = [
            'hash' => 'foo-bar',
            'password' => '12345-foo-bar',
        ];

        $this->mockDependence(ResetPassword::class, function (ResetPassword $resetPassword) use ($user, $data) {
            $resetPassword
                ->shouldReceive('where')->with('userId', $user->id)->once()->andReturnSelf()
                ->shouldReceive('where')->with('enable', true)->once()->andReturnSelf()
                ->shouldReceive('where')->with('hash', $data['hash'])->once()->andReturnSelf()
                ->shouldReceive('whereDate')->with('validatedAt', Mockery::on(function (Carbon $date) {
                    return $date->toString() === Carbon::today()->toString();
                }))->once()->andReturnSelf()
                ->shouldReceive('firstOrFail')->once()->andReturnSelf()
                ->shouldReceive('update')->with(['enable' => false])->once();

            return $resetPassword;
        });

        $user->shouldReceive('update')->with(['password' => $data['password']])->once();

        $this->app->make(AccountService::class)->resetPasswordUpdate($user, $data);
    }

    public function testMustGenerateHashTokenForRestPassword()
    {
        Carbon::setTestNow();

        $this->expectsEvents(CreateResetPassword::class);

        $user = Mockery::mock(User::factory()->make());

        $this->mockDependence(ResetPassword::class, function (ResetPassword $resetPassword) use ($user) {
            $resetPassword
                ->shouldReceive('where')->with('userId', $user->id)->once()->andReturnSelf()
                ->shouldReceive('whereDate')->with('created_at', Mockery::on(function (Carbon $date) {
                    return $date->toString() === Carbon::today()->toString();
                }))->once()->andReturnSelf()
                ->shouldReceive('count')->once()->andReturn(2)
                ->shouldReceive('where')->with('userId', $user->id)->once()->andReturnSelf()
                ->shouldReceive('update')->with(['enable' => false])->once();

            return $resetPassword;
        });

        $resetPasswords = Mockery::mock(HasMany::class);

        $resetPasswords->shouldReceive('create')->with(Mockery::on(function ($data) {
            if (! isset($data['hash'])) {
                return false;
            } else {
                unset($data['hash']);
            }

            $data['validatedAt'] = $data['validatedAt']->toString();

            $expected = [
                'validatedAt' => Carbon::today()->toString(),
                'enable' => true,
            ];

            return $data === $expected;
        }))->once()->andReturn(new ResetPassword());

        $user->shouldReceive('resetPasswords')->once()->andReturn($resetPasswords);

        $this->app->make(AccountService::class)->resetPassword($user);
    }

    public function testMustGenerateHashTokenForRestPasswordError()
    {
        Carbon::setTestNow();

        $this->expectException(ValidatorException::class);

        $user = Mockery::mock(User::factory()->make());

        $this->mockDependence(ResetPassword::class, function (ResetPassword $resetPassword) use ($user) {
            $resetPassword
                ->shouldReceive('where')->with('userId', $user->id)->once()->andReturnSelf()
                ->shouldReceive('whereDate')->with('created_at', Mockery::on(function (Carbon $date) {
                    return $date->toString() === Carbon::today()->toString();
                }))->once()->andReturnSelf()
                ->shouldReceive('count')->once()->andReturn(6);

            return $resetPassword;
        });

        $this->app->make(AccountService::class)->resetPassword($user);
    }

    public function testOrderedForForgotItMyPassword()
    {
        $this->expectException(ModelNotFoundException::class);

        $email = 'foo-email#mail.com';

        $this->mockDependence(User::class, function ($user) use ($email) {
            $user->shouldReceive('where')->with('email', $email)->once()->andReturnSelf()
                ->shouldReceive('firstOrFail')->once()->andThrow(ModelNotFoundException::class);

            return $user;
        });

        $this->app->make(AccountService::class)->forgotPassword($email);
    }

    public function testConfirmationForgotItMyPassword()
    {
        $this->expectException(ModelNotFoundException::class);

        $hash = 'foo-hash-12345';
        $email = 'foo-email#mail.com';

        $this->mockDependence(ResetPassword::class, function ($resetPassword) use ($hash) {
            $resetPassword->shouldReceive('where')->with('hash', $hash)->once()->andReturnSelf()
                ->shouldReceive('with')->with('user')->once()->andReturnSelf()
                ->shouldReceive('firstOrFail')->once()->andThrow(ModelNotFoundException::class);

            return $resetPassword;
        });

        $this->app->make(AccountService::class)->forgotPasswordConfirmation($hash, $email);
    }
}
