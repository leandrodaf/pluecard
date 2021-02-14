<?php

use App\Models\Item;
use App\Models\Payment\Card;
use App\Models\Payment\Payer;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use App\Services\TransactionService;

class TransactionServiceTest extends TestCase
{
    public function testCreateTransactionPassPayment()
    {
        $payment = new Payment(['user_id' => 1234]);
        $payment->id = 1;

        $payer = new Payer();
        $payer->id = 2;

        $card = new Card();
        $card->id = 3;

        $item = new Item();
        $item->id = 4;

        $data = [
            'user_id' => 1234,
            'item_id' => 4,
            'payment_id' => 1,
            'payer_id' => 2,
            'card_id' => 3,
        ];

        $this->mockDependence(Transaction::class, function (Transaction $transaction) use ($data) {
            $transaction
                ->shouldReceive('create')
                ->with($data)
                ->once()
                ->andReturn(new Transaction());

            return $transaction;
        });

        $this->app->make(TransactionService::class)->createByPayment($payment, $payer, $card, $item, $data);
    }
}
