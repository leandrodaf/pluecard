<?php

namespace App\Services;

use App\Models\Payment\Payer;
use App\Models\Payment\Payment;

class PayerService
{
    private $payer;

    public function __construct(Payer $payer)
    {
        $this->payer = $payer;
    }

    /**
     * Create Payer by Payment.
     *
     * @param Payment $payment
     * @param array $data
     * @return Payer
     */
    public function createByPayment(Payment $payment, array $data): Payer
    {
        $data['user_id'] = $payment->user_id;
        $data['payment_id'] = $payment->id;

        return $this->payer->create($data);
    }
}
