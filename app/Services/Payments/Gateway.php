<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class Gateway
{
    protected $data = [];

    protected $validate = [];

    protected function validation(): void
    {
        $validator = Validator::make($this->data, $this->validate);

        throw_if($validator->fails(), ValidationException::class, ($validator));
    }
}
