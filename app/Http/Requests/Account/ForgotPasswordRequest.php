<?php

namespace App\Http\Requests;

class ForgotPasswordRequest extends RequestValidatorAbstract implements RequestValidatorContract
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
