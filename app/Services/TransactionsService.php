<?php

namespace App\Services;

use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;

class TransactionsService
{
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Create Transaction by Payment.
     *
     * @param Payment $payment
     * @param array $data
     * @return Transaction
     */
    public function createByPayment(Payment $payment, array $data): Transaction
    {
        $data['user_id'] = $payment->user_id;
        $data['payment_id'] = $payment->payment_id;

        return $this->transaction->create($data);
    }
}
