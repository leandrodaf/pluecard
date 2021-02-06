<?php

namespace App\Services\Payments;

interface GatewayInterface
{
    public function payment();
}
