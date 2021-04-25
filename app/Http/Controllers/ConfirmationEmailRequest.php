<?php

namespace App\Http\Requests;

class ConfirmationEmailRequest extends RequestValidatorAbstract implements RequestValidatorContract
{
    public function rules(): array
    {
        return [
            'hash' => 'required|string',
        ];
    }
}
