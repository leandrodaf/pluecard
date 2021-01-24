<?php

namespace App\Http\Transformers;

use Illuminate\Validation\ValidationException;
use League\Fractal\TransformerAbstract;
use Throwable;

class ExceptionTransformer extends TransformerAbstract
{
    public function transform(Throwable $exception)
    {
        $body = [];

        if ($exception instanceof ValidationException) {
            $body['errors'] = $exception->errors();
        }

        if (env('APP_DEBUG')) {
            $body['code'] = $exception->getCode();
            $body['message'] = $exception->getMessage();
            $body['exception'] = (string) $exception;
            $body['line'] = $exception->getLine();
            $body['file'] = $exception->getFile();
        }

        return $body;
    }
}
