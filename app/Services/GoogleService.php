<?php

namespace App\Services;

use Google\Client;
use Google_Service_Oauth2;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GoogleService implements SocialAuthInterface
{
    private $client;

    private $tokenValidator = [
        'token_type' => 'required|string',
        'access_token' => 'required|string',
        'scope' => 'required|string',
        'login_hint' => 'required|string',
        'expires_in' => 'required|integer',
        'id_token' => 'required|string',
        'session_state' => 'required|array',
        'session_state.extraQueryParams' => 'required|array',
        'session_state.extraQueryParams.authuser' => 'required|string',

    ];

    public function __construct(Client $googleClient)
    {
        $this->client = $googleClient;

        $this->configurationClient();
    }

    private function validateToken(array $googleToken): void
    {
        $validator = Validator::make($googleToken, $this->tokenValidator);

        throw_if($validator->fails(), ValidationException::class, $validator);
    }

    public function setToken(array $googleToken): void
    {
        $this->validateToken($googleToken);

        $this->client->setAccessToken($googleToken);
    }

    private function configurationClient()
    {
        $this->client->setClientId(config('services.google.clientId'));
        $this->client->setClientSecret(config('services.google.clientSecret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    public function getUserinfo(): array
    {
        throw_if($this->client->isAccessTokenExpired(), AuthorizationException::class);

        $google_oauth = new Google_Service_Oauth2($this->client);
        $google_account_info = $google_oauth->userinfo->get();

        return [
            'email' => $google_account_info->email,
            'name' => $google_account_info->name,
            'id' => $google_account_info->id,
        ];
    }
}
