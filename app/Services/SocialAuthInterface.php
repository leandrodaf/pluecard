<?php

namespace App\Services;

interface SocialAuthInterface
{
    public function setToken(array $googleToken): void;

    public function getUserinfo(): array;
}
