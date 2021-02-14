<?php

use App\Exceptions\ValidatorException;
use App\Models\Item;
use App\Services\Payments\GatewayMethod;
use App\Services\Payments\MercadoPagoGateway;

class GatewayMethodTest extends TestCase
{
    public function testMustGetGatewatPaymentMethid()
    {
        $gateway = 'mercado-pago';
        $item = Item::factory()->make();
        $data = [];

        $result = $this->app->make(GatewayMethod::class)->get($gateway, $item, $data);

        $this->assertInstanceOf(MercadoPagoGateway::class, $result);
    }

    public function testGatewatNotFound()
    {
        $gateway = 'itau';
        $item = Item::factory()->make();
        $data = [];

        $this->expectException(ValidatorException::class);

        $this->app->make(GatewayMethod::class)->get($gateway, $item, $data);
    }
}
