<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Payment\Card;
use App\Models\Payment\Payer;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use App\Models\User;
use Illuminate\Pagination\Paginator;

class TransactionService
{
    public function __construct(
        private Transaction $transaction
    ) {
    }

    /**
     * Create new Payment with relations.
     *
     * @param Payment $payment
     * @param Payer $payer
     * @param Card $card
     * @param Item $item
     * @param array $data
     * @return Transaction
     */
    public function createByPayment(Payment $payment, Payer $payer, Card $card, Item $item, array $data): Transaction
    {
        $data = [
            'user_id' => $payment->user_id,
            'item_id' => $item->id,
            'payment_id' => $payment->id,
            'payer_id' => $payer->id,
            'card_id' => $card->id,
        ];

        return $this->transaction->create($data);
    }

    /**
     * List all Transaction for specific user with paginate.
     *
     * @param User $user
     * @return Paginator
     */
    public function listTransactionsPaginate(User $user): Paginator
    {
        return $this->transaction->where('user_id', $user->id)->simplePaginate(15);
    }

    /**
     * Show Single User.
     *
     * @param User $user
     * @param string $id
     * @return Transaction
     */
    public function show(User $user, string $id): Transaction
    {
        return $this->transaction
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
    }
}
