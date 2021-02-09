<?php

namespace App\Services\Payments;

use App\Models\Payment\PaymentItem;

interface GatewayInterface
{
    public function payment();

    public function getItem(): PaymentItem;
}
