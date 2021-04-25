<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\RequestValidatorAbstract;
use App\Http\Requests\RequestValidatorContract;

class ForgotPasswordRequest extends RequestValidatorAbstract implements RequestValidatorContract
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
