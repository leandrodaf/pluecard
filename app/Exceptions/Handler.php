<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use App\Http\Transformers\ExceptionTransformer;
use Google\Service\Exception as GoogleException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Custom status texts.
     *
     * @var array
     */
    private static $customStatusTexts = [
        Response::HTTP_BAD_REQUEST => 'BadRequestError', // 400
        Response::HTTP_UNAUTHORIZED => 'UnauthorizedError', // 401
        Response::HTTP_NOT_FOUND => 'NotFoundError', // 404
        Response::HTTP_CONFLICT => 'ConflictError', // 409
        Response::HTTP_INTERNAL_SERVER_ERROR => 'InternalError', // 500
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $response = [
            'code' => $this->getStatusText(Response::HTTP_INTERNAL_SERVER_ERROR),
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => (string) $exception->getMessage(),
        ];

        if ($exception instanceof AuthorizationException || $exception instanceof GoogleException) {
            $response['code'] = $this->getStatusText(Response::HTTP_UNAUTHORIZED);
            $response['status'] = Response::HTTP_UNAUTHORIZED;
        } elseif ($exception instanceof HttpException) {
            $response['code'] = $this->getStatusText($exception->getStatusCode());
            $response['status'] = $exception->getStatusCode();
        } elseif ($exception instanceof ModelNotFoundException) {
            $response['code'] = $this->getStatusText(Response::HTTP_NOT_FOUND);
            $response['status'] = Response::HTTP_NOT_FOUND;
        } elseif ($exception instanceof QueryException) {
            $response['code'] = $this->getStatusText(Response::HTTP_CONFLICT);
            $response['status'] = Response::HTTP_CONFLICT;
            $response['message'] = $this->sanitizeMySQLConflictError($exception->getMessage());
        } elseif ($exception instanceof ValidationException) {
            $response['code'] = $this->getStatusText(Response::HTTP_BAD_REQUEST);
            if (array_key_first($exception->validator->errors()->toArray()) === 0) {
                $response['errors'] = $exception->validator->errors()->all();
            } else {
                $response['errors'] = $exception->validator->errors()->toArray();
            }

            $response['status'] = Response::HTTP_BAD_REQUEST;
            $response['message'] = 'One or more fields are invalid';
        } elseif ($exception instanceof AuthenticationException) {
            $response['code'] = $this->getStatusText(Response::HTTP_UNAUTHORIZED);
            $response['status'] = Response::HTTP_UNAUTHORIZED;
            $response['message'] = '[auth] Invalid user or password';
        }

        $response['debug'] = (string) $exception;

        return response()->item($response, new ExceptionTransformer, $response['status']);
    }

    private function getStatusText(int $code): string
    {
        $statusTexts = self::$customStatusTexts + Response::$statusTexts;

        return $statusTexts[$code];
    }

    private function sanitizeMySQLConflictError($error)
    {
        try {
            preg_match('/SQLSTATE\[[0-9]+\]: ([a-zA-Z0-9\s:\'-_]+) \(.*/', $error, $matches);

            return $matches[1];
        } catch (Exception $e) {
            return 'Integrity constraint violation';
        }
    }
}
