<?php

namespace App\Http\Transformers;

use App\Models\Payment\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user', 'item', 'payment', 'card', 'payer',
    ];

    public function transform(Transaction $transaction)
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

    public function includeUser(Transaction $ptransaction)
    {
        return $this->item($ptransaction->user, new UserTransformer);
    }

    public function includeItem(Transaction $ptransaction)
    {
        return $this->item($ptransaction->item, new ItemTransformer);
    }

    public function includePayment(Transaction $ptransaction)
    {
        return $this->item($ptransaction->payment, new PaymentTransformer);
    }

    public function includeCard(Transaction $ptransaction)
    {
        return $this->item($ptransaction->card, new CardTransformer);
    }

    public function includePayer(Transaction $ptransaction)
    {
        return $this->item($ptransaction->payer, new PayerTransformer);
    }
}
