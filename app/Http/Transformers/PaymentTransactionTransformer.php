<?php

namespace App\Http\Transformers;

use App\Models\Payment\PaymentTransactions;
use League\Fractal\TransformerAbstract;

class PaymentTransactionTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user', 'item', 'payment', 'card', 'payer',
    ];

    public function transform(PaymentTransactions $transaction)
    {
        return [
            'id' => $transaction->id,
            'currency_id' => $transaction->currency_id,
            'amount' => $transaction->amount,
            'quantity' => $transaction->quantity,
            'status' => $transaction->status,
            'installments' => $transaction->installments,
            'created_at' => $transaction->created_at,
            'updated_at' => $transaction->updated_at,
        ];
    }

    public function includeUser(PaymentTransactions $ptransaction)
    {
        return $this->item($ptransaction->user, new UserTransformer);
    }

    public function includeItem(PaymentTransactions $ptransaction)
    {
        return $this->item($ptransaction->item, new ItemTransformer);
    }

    public function includePayment(PaymentTransactions $ptransaction)
    {
        return $this->item($ptransaction->payment, new PaymentTransformer);
    }

    public function includeCard(PaymentTransactions $ptransaction)
    {
        return $this->item($ptransaction->card, new CardTransformer);
    }

    public function includePayer(PaymentTransactions $ptransaction)
    {
        return $this->item($ptransaction->payer, new PayerTransformer);
    }
}
