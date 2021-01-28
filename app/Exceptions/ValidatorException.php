<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidatorException extends ValidationException
{
    public function __construct($errors, $response = null, $errorBag = 'default')
    {
        $validator = Validator::make([], []);

        foreach ($errors as $key => $value) {
            $validator->errors()->add($key, $value);
        }

        parent::__construct($validator, $response, $errorBag);
    }
}
