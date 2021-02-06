<?php

namespace App\Listeners;

use App\Events\PaymentCreated;
use App\Services\PaymentService;

class AssociateToUser
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handle(PaymentCreated $event)
    {
        $this->paymentService->associate($event->user, $event->payment);
    }
}
