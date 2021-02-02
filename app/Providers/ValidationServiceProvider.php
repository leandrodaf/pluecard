<?php

namespace App\Providers;

use App\Http\Validators\Base64\ImageValidator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        ValidatorFacade::extend('base64_image', function ($attribute, $value, $parameters, $validator) {
            return (new ImageValidator())->validateBase64Image($attribute, $value, $parameters, $validator);
        }, ['Base64 is not a valid image or the mime type is invalid.']);
    }
}
