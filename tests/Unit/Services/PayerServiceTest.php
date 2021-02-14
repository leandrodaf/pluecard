<?php

use App\Models\Payment\Payer;
use App\Models\Payment\Payment;
use App\Services\PayerService;

class PayerServiceTest extends TestCase
{
    public function testCreatePayerPassPayment()
    {
        $payment = new Payment(['user_id' => 1234]);
        $payment->id = 1;
        $data = [
            'first_six_digits' => '123456',
            'user_id' => 1234,
            'payment_id' => 1,
        ];

        $this->mockDependence(Payer::class, function (Payer $payer) use ($data) {
            $payer
                ->shouldReceive('create')
                ->with($data)
                ->once()
                ->andReturn(new Payer());

            return $payer;
        });

        $this->app->make(PayerService::class)->createByPayment($payment, $data);
    }
}
