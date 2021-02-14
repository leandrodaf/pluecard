<?php

namespace App\Services\Payments;

use App\Exceptions\ValidatorException;
use App\Models\Item;

class GatewayMethod
{
    private $gatewayList = [
        'mercado-pago' => MercadoPagoGateway::class,
    ];

    /**
     * Select specific payment dateway.
     *
     * @param string $gateway
     * @param Item $item
     * @param array $data
     * @return GatewayInterface
     * @throws Throwable
     */
    public function get(string $gateway, Item $item, array $data): GatewayInterface
    {
        throw_unless(array_key_exists($gateway, $this->gatewayList), ValidatorException::class, ['gateway' => "Notfound  gateway $gateway"]);

        return new $this->gatewayList[$gateway]($item, $data);
    }
}
