<?php

namespace App\Http\Validators\Base64;

use App\Services\Helpers\Base64Service;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Illuminate\Validation\Validator;

class ImageValidator
{
    use ValidatesAttributes;

    public function validateBase64Image(string $attribute, $value, array $parameters, Validator $validator): bool
    {
        if (empty($value)) {
            return false;
        }

        $base64Service = app(Base64Service::class);

        $file = $base64Service->convertToFile($value);

        return $validator->validateImage($attribute, $file);
    }
}
