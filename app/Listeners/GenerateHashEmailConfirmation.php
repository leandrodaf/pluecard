<?php

namespace App\Listeners;

use App\Events\UserCreate;
use App\Services\AuthService;

class GenerateHashEmailConfirmation
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(UserCreate $event)
    {
        $this->authService->generateHashConfirmation($event->user);
    }
}
