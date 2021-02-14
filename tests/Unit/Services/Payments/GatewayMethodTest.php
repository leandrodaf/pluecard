<?php

use App\Exceptions\ValidatorException;
use App\Models\Item;
use App\Services\Payments\GatewayMethod;
use App\Services\Payments\MercadoPagoGateway;
use Illuminate\Support\Facades\Config;

class GatewayMethodTest extends TestCase
{
    public function testMustGetGatewatPaymentMethid()
    {
        $gateway = 'mercado-pago';
        $item = Item::factory()->make();
        $data = [];

        Config::set('payment.mercadopago.secret', 'TEST-3333644868245781-020502-2cf74afc663e26a3bd176206c0b749ed-205648376');

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
