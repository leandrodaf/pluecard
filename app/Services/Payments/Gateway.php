<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

abstract class Gateway
{
    protected $data = [];

    protected $validate = [];

    /**
     * Validate payment map gateway.
     *
     * @return void
     * @throws Throwable
     */
    protected function validation(): void
    {
        $validator = Validator::make($this->data, $this->validate);

        throw_if($validator->fails(), ValidationException::class, ($validator));
    }
}
