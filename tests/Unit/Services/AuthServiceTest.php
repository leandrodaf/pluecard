<?php

use App\Events\CreateConfirmationAccount;
use App\Exceptions\ValidatorException;
use App\Models\ConfirmationAccount;
use App\Models\User;
use App\Services\AuthService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class AuthServiceTest extends TestCase
{
    public function testMustAuthentication()
    {
        $username = 'foo-user';

        $password = 'foo-bar-12345';

        $fakeToken = 'foo-token';

        $user = User::factory()->make(['confirmation_email' => true]);

        Auth::shouldReceive('attempt')->with(['email' => $username, 'password' => $password])->once()->andReturn($fakeToken);
        Auth::shouldReceive('user')->andReturn($user);

        $token = $this->app->make(AuthService::class)->login($username, $password);

        $this->assertEquals($fakeToken, $token);
    }

    public function testMustAuthenticationUserNotFound()
    {
        $this->expectException(AuthorizationException::class);

        $username = 'foo-user';

        $password = 'foo-bar-12345';

        Auth::shouldReceive('attempt')->with(['email' => $username, 'password' => $password])->once()->andReturn(null);

        $this->app->make(AuthService::class)->login($username, $password);
    }

    public function testMustAuthenticationUserAccountNotConfirmation()
    {
        $this->expectException(ValidatorException::class);

        $username = 'foo-user';

        $password = 'foo-bar-12345';

        $fakeToken = 'foo-token';

        $user = User::factory()->make(['confirmation_email' => false]);

        Auth::shouldReceive('attempt')->with(['email' => $username, 'password' => $password])->once()->andReturn($fakeToken);
        Auth::shouldReceive('user')->andReturn($user);

        $this->app->make(AuthService::class)->login($username, $password);
    }

    public function testMustNotGenerateHashConfirmation()
    {
        $this->expectException(ValidatorException::class);

        $user = User::factory()->make(['confirmation_email' => true]);

        $this->app->make(AuthService::class)->generateHashConfirmation($user);
    }

    public function testMusGenerateHashConfirmation()
    {
        Carbon::setTestNow();

        $this->expectsEvents(CreateConfirmationAccount::class);

        $user = Mockery::mock(User::factory()->make(['confirmation_email' => false]));

        $confirmationAccount = Mockery::mock(HasOne::class);

        $confirmationAccount->shouldReceive('create')->with(Mockery::on(function (array $newHash) {
            return array_key_exists('hash', $newHash) && $newHash['validated_at']->toString() === Carbon::now()->addDays(1)->toString();
        }))->once()->andReturn(new ConfirmationAccount());

        $user->shouldReceive('confirmationAccount')->once()->andReturn($confirmationAccount);

        $this->app->make(AuthService::class)->generateHashConfirmation($user);
    }

    public function testMusGenerateHashConfirmationNewHash()
    {
        Carbon::setTestNow();

        $this->expectsEvents(CreateConfirmationAccount::class);

        $user = Mockery::mock(User::factory()->make(['confirmation_email' => false]));

        $confirmationAccount = Mockery::mock(ConfirmationAccount::class);

        $confirmationAccount->shouldReceive('fill')->with(Mockery::on(function (array $newHash) {
            return array_key_exists('hash', $newHash)
                && $newHash['validated_at']->toString() === Carbon::now()->addDays(1)->toString();
        }))->once()->andReturnSelf()
            ->shouldReceive('save')->once();

        $user->confirmationAccount = $confirmationAccount;

        $this->app->make(AuthService::class)->generateHashConfirmation($user);
    }

    public function testHashConfirmation()
    {
        Carbon::setTestNow();

        $fakeHash = 'foo-bar-hash';

        $this->mockDependence(ConfirmationAccount::class, function ($confirmationAccount) use ($fakeHash) {
            $user = Mockery::mock(User::class);

            $user->shouldReceive('update')->with(['confirmation_email' => true])->once();

            $mockConfirmationAccount = Mockery::mock(new stdClass());

            $mockConfirmationAccount->shouldReceive('update')->with(Mockery::on(function ($teste) {
                return $teste['validated_at']->toString() === Carbon::now()->toString();
            }))->once();

            $mockConfirmationAccount->user = $user;

            $confirmationAccount->shouldReceive('where')->with('hash', $fakeHash)->andReturnSelf()
                ->shouldReceive('whereDate')->with('validated_at', '>', Mockery::on(function ($dateNow) {
                    return $dateNow->toString() === Carbon::now()->toString();
                }))->andReturnSelf()
                ->shouldReceive('with')->with('user')->once()->andReturnSelf()
                ->shouldReceive('firstOrFail')->once()->andReturn($mockConfirmationAccount);

            return $confirmationAccount;
        });

        $this->app->make(AuthService::class)->hashConfirmation($fakeHash);
    }

    public function testRefreshNewCash()
    {
        $this->expectException(ValidatorException::class);

        $email = 'foo@mail.com';

        $this->mockDependence(User::class, function ($user) use ($email) {
            $user->shouldReceive('where')->with('email', $email)->once()->andReturnSelf()
                ->shouldReceive('where')->with('confirmation_email', '!=', true)->once()->andReturnSelf()
                ->shouldReceive('firstOrFail')->once()->andReturn(User::factory()->make(['confirmation_email' => true]));

            return $user;
        });

        $this->app->make(AuthService::class)->refreshHash($email);
    }
}
