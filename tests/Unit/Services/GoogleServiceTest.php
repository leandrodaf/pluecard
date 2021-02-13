<?php

use App\Services\GoogleOauuth2Service;
use App\Services\GoogleService;
use Google\Client;
use Illuminate\Support\Facades\Config;

class GoogleServiceTest extends TestCase
{
    public function testSetGoogleAuthTokenInClient()
    {
        $token = [
            'token_type' => 'foo-token',
            'access_token' => 'foo-access-token',
            'scope' => 'email',
            'login_hint' => 'tested-foo',
            'expires_in' => '10000',
            'id_token' => '12345-foo-toen',
            'session_state' => [
                'extraQueryParams' => [
                    'authuser' => 'tested',
                ],
            ],
        ];

        $this->mockDependence(Client::class, function (Client $client) use ($token) {
            Config::set('services.google.clientId', 'foo-1');
            Config::set('services.google.clientSecret', 'foo-2');
            Config::set('services.google.redirect', 'foo-3');

            $client->shouldReceive('setClientId')->with('foo-1')->once();
            $client->shouldReceive('setClientSecret')->with('foo-2')->once();
            $client->shouldReceive('setRedirectUri')->with('foo-3')->once();
            $client->shouldReceive('addScope')->once();
            $client->shouldReceive('addScope')->once();

            $client->shouldReceive('setAccessToken')->with($token)->once();

            return $client;
        });

        $this->app->make(GoogleService::class)->setToken($token);
    }

    public function testMustReturndUserInfo()
    {
        $token = [
            'token_type' => 'foo-token',
            'access_token' => 'foo-access-token',
            'scope' => 'email',
            'login_hint' => 'tested-foo',
            'expires_in' => '10000',
            'id_token' => '12345-foo-toen',
            'session_state' => [
                'extraQueryParams' => [
                    'authuser' => 'tested',
                ],
            ],
        ];

        $this->mockDependence(Client::class, function (Client $client) use ($token) {
            Config::set('services.google.clientId', 'foo-1');
            Config::set('services.google.clientSecret', 'foo-2');
            Config::set('services.google.redirect', 'foo-3');

            $client->shouldReceive('setClientId')->with('foo-1')->once();
            $client->shouldReceive('setClientSecret')->with('foo-2')->once();
            $client->shouldReceive('setRedirectUri')->with('foo-3')->once();
            $client->shouldReceive('addScope')->once();
            $client->shouldReceive('addScope')->once();

            $client->shouldReceive('isAccessTokenExpired')->once()->andReturn(false);

            return $client;
        });

        $this->mockDependence(GoogleOauuth2Service::class, function (GoogleOauuth2Service $foogleOauuth2Service) {
            $google_Service_Oauth2 = Mockery::mock(Google_Service_Oauth2::class);
            $google_Service_Oauth2_Resource_Userinfo = Mockery::mock(Google_Service_Oauth2_Resource_Userinfo::class);

            $google_Service_Oauth2_Userinfo = Mockery::mock(Google_Service_Oauth2_Userinfo::class);

            $google_Service_Oauth2_Userinfo->name = 'foo-name';
            $google_Service_Oauth2_Userinfo->email = 'foo-email';
            $google_Service_Oauth2_Userinfo->id = 100000;

            $google_Service_Oauth2_Resource_Userinfo->shouldReceive('get')->once()->andReturn($google_Service_Oauth2_Userinfo);

            $google_Service_Oauth2->userinfo = $google_Service_Oauth2_Resource_Userinfo;

            $foogleOauuth2Service->shouldReceive('getGoogleOauth2')->once()->andReturn($google_Service_Oauth2);

            return $foogleOauuth2Service;
        });

        $fakeUser = $this->app->make(GoogleService::class)->getUserinfo($token);

        $this->assertEquals(['email' => 'foo-email', 'name' => 'foo-name', 'id' => 100000], $fakeUser);
    }
}
