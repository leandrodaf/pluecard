<?php

namespace App\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Throwable;

class SocialAuthService
{
    private $avaliableChanels = [
        'google' => GoogleService::class,
    ];

    /**
     * Select payment driver in avaliable chanels.
     *
     * @param string $channelId
     * @param array $authToken
     * @return SocialAuthInterface
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function getDriver(string $channelId, array $authToken): SocialAuthInterface
    {
        throw_unless(array_key_exists($channelId, $this->avaliableChanels), ValidatorException::class, 'The requested driver does not exist');

        $driver = app($this->avaliableChanels[$channelId]);

        $driver->setToken($authToken);

        return $driver;
    }
}
