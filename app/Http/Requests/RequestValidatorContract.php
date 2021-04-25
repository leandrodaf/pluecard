<?php

namespace App\Http\Requests;

use Laravel\Lumen\Http\Request;

interface RequestValidatorContract
{
    /**
     * Validate request.
     *
     * @return array
     */
    public function validator(Request $request);

    /**
     * Rules Validator.
     *
     * @return array
     */
    public function rules(): array;

    /**
     * Custom Message validator.
     * @return array
     */
    public function messages(): array;
}
