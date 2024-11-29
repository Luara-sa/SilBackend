<?php

namespace App\Exceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class handler extends ExceptionHandler
{

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->jsonResponse(false, 'Unauthenticated', [], 401);
        }
    }


    public function render($request, Throwable $exception)
    {


        if ($exception instanceof ModelNotFoundException) {
            return response()-jsonResponse(false, __('messages.not_found'), [], 404);
        }
        if ($exception instanceof TooManyRequestsHttpException) {
            return  response()->jsonResponse(false, __('messages.too_many_requests'), [], 429);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->jsonResponse(false, __('messages.method_not_allowed'), [], 405);
        }

        if ($exception instanceof ValidationException) {
            return response()->jsonResponse(false, $exception->getMessage(), $exception->errors(), 422);
        }


//        if ($request->expectsJson()) {
//            return responseJson(false, $exception->getMessage(), [], 422);
//        }
        return parent::render($request, $exception);
    }

}
