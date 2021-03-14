<?php

namespace App\Services;

use Google\Client;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GoogleService implements SocialAuthInterface
{
    private $tokenValidator = [
        'token_type' => 'string',
        'access_token' => 'string',
        'scope' => 'string',
        'login_hint' => 'string',
        'expires_in' => 'integer',
        'id_token' => 'string',
        'session_state' => 'array',
        'session_state.extraQueryParams' => 'array',
        'session_state.extraQueryParams.authuser' => 'string',
    ];

    public function __construct(
        private Client $googleClient,
        private  GoogleOauuth2Service $googleOauuth2Service
    ) {
        $this->client = $googleClient;

        $this->googleOauuth2Service = $googleOauuth2Service;

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

    private function configurationClient(): void
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

        $google_account_info = $this->googleOauuth2Service->getGoogleOauth2($this->client)->userinfo->get();

        return [
            'email' => $google_account_info->email,
            'name' => $google_account_info->name,
            'id' => $google_account_info->id,
        ];
    }
}
