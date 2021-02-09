<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\UserCreate::class => [
            \App\Listeners\GenerateHashEmailConfirmation::class,
        ],

        \App\Events\CreateConfirmationAccount::class => [
            \App\Listeners\EmailConfirmation::class,
        ],

        \App\Events\CreateResetPassword::class => [
            \App\Listeners\ResetPassword::class,
        ],

        \App\Events\PaymentCreated::class => [
            \App\Listeners\AssociateToUser::class,
        ],
    ];
}