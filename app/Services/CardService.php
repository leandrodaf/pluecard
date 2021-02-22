<?php

namespace App\Services;

use App\Models\Payment\Card;
use App\Models\Payment\Payment;

class CardService
{
    public function __construct(private Card $card)
    {
    }

    /**
     * Create Card by Payment.
     *
     * @param Payment $payment
     * @param array $data
     * @return Card
     */
    public function createByPayment(Payment $payment, array $data): Card
    {
        $data['user_id'] = $payment->user_id;
        $data['payment_id'] = $payment->id;

        return $this->card->create($data);
    }
}
