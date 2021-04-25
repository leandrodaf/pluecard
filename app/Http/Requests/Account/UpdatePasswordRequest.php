<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\RequestValidatorAbstract;
use App\Http\Requests\RequestValidatorContract;

class UpdatePasswordRequest extends RequestValidatorAbstract implements RequestValidatorContract
{
    public function rules(): array
    {
        return [
            'hash' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ];
    }
}
