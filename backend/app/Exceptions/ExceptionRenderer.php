<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExceptionRenderer
{
    use ApiResponse;

    public function render(Throwable $e, $request)
    {
        if ($e instanceof ModelNotFoundException) {
            return $this->errorResponse('Resource not found', Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof ValidationException) {
            return $this->errorResponse('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $e->errors());
        }

        $message = app()->environment('production') ? 'An error occurred' : $e->getMessage();
        $status = $e->getCode() && $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        return $this->errorResponse($message, $status);
    }
}
