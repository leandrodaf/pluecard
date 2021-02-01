<?php

namespace App\Http\Validators\Base64;

use Illuminate\Validation\Concerns\ValidatesAttributes;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\File\File;

class ImageValidator
{
    use ValidatesAttributes;

    public function validateBase64Image(string $attribute, $value, array $parameters, Validator $validator): bool
    {

        dd(
            (boolean)preg_match('/^data:image\/(\w+);base64,/', $value, $type)
        );
        if (empty($value) ) {
            return false;
        }

        $file = $this->convertToFile($value);

        $isTrue = $validator->validateImage($attribute, $file);

        if (count($parameters) > 0) {
            $isTrue = $isTrue && $validator->validateMimes($attribute, $file, $parameters);
        }

        return $isTrue;
    }

    /**
     * @param string $value
     *
     * @return File
     */
    private function convertToFile(string $value): File
    {
        if (strpos($value, ';base64') !== false) {
            [, $value] = explode(';', $value);
            [, $value] = explode(',', $value);
        }

        $binaryData = base64_decode($value);
        $tmpFile = tempnam(sys_get_temp_dir(), 'base64validator');
        file_put_contents($tmpFile, $binaryData);

        return new File($tmpFile);
    }
}
