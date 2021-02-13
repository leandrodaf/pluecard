<?php

namespace App\Services;

use App\Models\Payment\Transaction;
use App\Models\User;
use Illuminate\Pagination\Paginator;

class TransactionService
{
    private $transaction;

    /**
     * @param Transaction $transaction
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param User $user
     * @return Paginator
     */
    public function listTransactionsPaginate(User $user): Paginator
    {
        return $this->transaction->where('user_id', $user->id)->simplePaginate(15);
    }

    /**
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
