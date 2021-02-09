<?php

namespace App\Http\Transformers;

use App\Models\Payment\Card;
use League\Fractal\TransformerAbstract;

class CardTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'payment', 'user',
    ];

    public function transform(Card $card)
    {
        return [
            'id' => $card->id,
            'external_id' => $card->external_id,
            'customer_id' => $card->customer_id,
            'expiration_month' => $card->expiration_month,
            'expiration_year' => $card->expiration_year,
            'first_six_digits' => $card->first_six_digits,
            'last_four_digits' => $card->last_four_digits,
            'payment_method' => $card->payment_method,
            'security_code' => $card->security_code,
            'issuer' => $card->issuer,
            'cardholder' => $card->cardholder,
            'date_created' => $card->date_created,
            'date_last_updated' => $card->date_last_updated,
            'last' => $card->last,
            'error' => $card->error,
            'pagination_params' => $card->pagination_params,
            'empty' => $card->empty,
            'user_id' => $card->user_id,
            'payment_id' => $card->payment_id,
            'created_at' => $card->created_at,
            'updated_at' => $card->updated_at,
        ];
    }

    public function includeUser(Card $card)
    {
        return $this->item($card->user, new UserTransformer);
    }

    public function includePayment(Card $card)
    {
        return $this->item($card->payment, new PaymentTransformer);
    }
}
