<?php

namespace App\Services;

class SocialAuthService
{
    private $avaliableChanels = [
        'google' => GoogleService::class,
    ];

    private function getChannel(string $channelId, array $authToken)
    {
        throw_unless(array_key_exists($channelId, $this->avaliableChanels), ValidatorException::class, 'The requested driver does not exist');

        $driver = app($this->avaliableChanels[$channelId]);

        $driver->setToken($authToken);

        return $driver;
    }

    public function getDriver(string $channel, array $authToken): SocialAuthInterface
    {
        return $this->getChannel($channel, $authToken);
    }
}
