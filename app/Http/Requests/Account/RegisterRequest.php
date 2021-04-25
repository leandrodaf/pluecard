<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\RequestValidatorAbstract;
use App\Http\Requests\RequestValidatorContract;

class RegisterRequest extends RequestValidatorAbstract implements RequestValidatorContract
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:155|min:3',
            'accept_terms' => 'accepted',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
            'newsletter' => 'required|boolean',
            'discount_coupons' => 'required|boolean',
        ];
    }
}
