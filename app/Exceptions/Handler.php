<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Traits\ApiResponse;

class Handler
{
    use ApiResponse;

    public function handle(Throwable $exception, $request)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof ValidationException) {
                return $this->unprocessableApiResponse(
                    $exception->errors(),
                    __('The given data was invalid.')
                );
            }

            if ($exception instanceof AuthenticationException) {
                return $this->unauthorizedApiResponse([], __('Unauthorized.'));
            }

            if ($exception instanceof AuthorizationException) {
                return $this->forbiddenApiResponse([], __('Forbidden.'));
            }

            if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                return $this->notFoundApiResponse([], __('Not Found.'));
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->errorApiResponse([], 405, __('Method Not Allowed.'));
            }

            return $this->errorApiResponse([], 500, __('Server Error.'));
        }

        // لو الريكوست مش API أو مش متوقع Json، نرمي الخطأ
        throw $exception;
    }
}
