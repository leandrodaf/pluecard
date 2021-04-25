<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\RequestValidatorAbstract;
use App\Http\Requests\RequestValidatorContract;

class ConfirmationEmailRequest extends RequestValidatorAbstract implements RequestValidatorContract
{
    public function rules(): array
    {
        return [
            'hash' => 'required|string',
        ];
    }
}
