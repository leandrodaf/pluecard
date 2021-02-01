<?php

namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
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
}
