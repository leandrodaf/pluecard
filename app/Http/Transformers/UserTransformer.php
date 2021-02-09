<?php

namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'rules', 'payments', 'transactions', 'payers', 'cards',
    ];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'accept_terms' => $user->accept_terms,
            'newsletter' => $user->newsletter,
            'discount_coupons' => $user->discount_coupons,
            'confirmation_email' => $user->confirmation_email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    public function includeRules(User $user)
    {
        return $this->item($user->getRoleNames(), new RulesTransformer);
    }

    public function includePayments(User $user)
    {
        return $this->collection($user->payments, new PaymentTransformer);
    }

    public function includeTransactions(User $user)
    {
        return $this->collection($user->transactions, new PaymentTransactionTransformer);
    }

    public function includePayers(User $user)
    {
        return $this->collection($user->payers, new PayerTransformer);
    }

    public function includeCards(User $user)
    {
        return $this->collection($user->cards, new CardTransformer);
    }
}
