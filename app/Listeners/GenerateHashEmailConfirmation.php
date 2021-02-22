<?php

namespace App\Listeners;

use App\Events\UserCreate;
use App\Services\AuthService;

class GenerateHashEmailConfirmation
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    public function handle(UserCreate $event)
    {
        $this->authService->generateHashConfirmation($event->user);
    }
}
