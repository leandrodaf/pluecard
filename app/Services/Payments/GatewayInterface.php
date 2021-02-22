<?php

namespace App\Services\Payments;

use App\Models\Item;

interface GatewayInterface
{
    /**
     * Make payment.
     * @return mixed
     */
    public function payment();

    /**
     * Returns the related item.
     *
     * @return Item
     */
    public function getItem(): Item;
}
