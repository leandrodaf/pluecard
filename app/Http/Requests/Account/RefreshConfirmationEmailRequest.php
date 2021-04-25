<?php

namespace App\Http\Requests;

class RefreshConfirmationEmailRequest extends RequestValidatorAbstract implements RequestValidatorContract
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
