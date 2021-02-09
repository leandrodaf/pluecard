<?php

namespace App\Http\Transformers;

use App\Models\Payment\Payer;
use League\Fractal\TransformerAbstract;

class PayerTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'payment', 'user',
    ];

    public function transform(Payer $payer)
    {
        return [
            'id' => $payer->id,
            'external_id' => $payer->external_id,
            'type' => $payer->type,
            'name' => $payer->name,
            'surname' => $payer->surname,
            'first_name' => $payer->first_name,
            'last_name' => $payer->last_name,
            'email' => $payer->email,
            'date_created' => $payer->date_created,
            'phone' => $payer->phone,
            'identification' => $payer->identification,
            'address' => $payer->address,
            'created_at' => $payer->created_at,
            'updated_at' => $payer->updated_at,
        ];
    }

    public function includeUser(Payer $payer)
    {
        return $this->item($payer->user, new UserTransformer);
    }

    public function includePayment(Payer $payer)
    {
        return $this->item($payer->payment, new PaymentTransformer);
    }
}
