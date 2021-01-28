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
            'acceptTerms' => $user->acceptTerms,
            'newsletter' => $user->newsletter,
            'discountCoupons' => $user->discountCoupons,
            'confirmationEmail' => $user->confirmationEmail,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
