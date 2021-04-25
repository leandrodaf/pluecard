<?php

namespace App\Http\Requests;

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
