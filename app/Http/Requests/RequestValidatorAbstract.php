<?php

namespace App\Http\Requests;

use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

abstract class RequestValidatorAbstract implements RequestValidatorContract
{
    use ProvidesConvenienceMethods;

    public function validator(Request $request)
    {
        return $this->validate($request, $this->rules(), $this->messages());
    }

    public function messages(): array
    {
        return [];
    }
}
