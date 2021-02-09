<?php

namespace App\Http\Transformers;

use App\Models\Payment\Transactions;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user', 'item', 'payment', 'card', 'payer',
    ];

    public function transform(Transactions $transaction)
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

    public function includeUser(Transactions $ptransaction)
    {
        return $this->item($ptransaction->user, new UserTransformer);
    }

    public function includeItem(Transactions $ptransaction)
    {
        return $this->item($ptransaction->item, new ItemTransformer);
    }

    public function includePayment(Transactions $ptransaction)
    {
        return $this->item($ptransaction->payment, new PaymentTransformer);
    }

    public function includeCard(Transactions $ptransaction)
    {
        return $this->item($ptransaction->card, new CardTransformer);
    }

    public function includePayer(Transactions $ptransaction)
    {
        return $this->item($ptransaction->payer, new PayerTransformer);
    }
}
