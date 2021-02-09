<?php

namespace App\Services\Payments;

use App\Models\Item;

interface GatewayInterface
{
    public function payment();

    public function getItem(): Item;
}
