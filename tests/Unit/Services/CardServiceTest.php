<?php

use App\Models\Payment\Card;
use App\Models\Payment\Payment;
use App\Services\CardService;

class CardServiceTest extends TestCase
{
    public function testCreateCardPassPayment()
    {
        $payment = new Payment(['user_id' => 1234]);
        $payment->id = 1;
        $data = [
            'first_six_digits' => '123456',
            'user_id' => 1234,
            'payment_id' => 1,
        ];

        $this->mockDependence(Card::class, function (Card $card) use ($data) {
            $card
                ->shouldReceive('create')
                ->with($data)
                ->once()
                ->andReturn(new Card());

            return $card;
        });

        $this->app->make(CardService::class)->createByPayment($payment, $data);
    }
}
