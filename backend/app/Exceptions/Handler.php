<?php

namespace App\Exceptions;

use App\Exceptions\AuthException;
use App\Exceptions\TokenException;
use App\Exceptions\UserNotFoundException;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    protected $dontReport = [
        AuthException::class,
        TokenException::class,
        UserNotFoundException::class,
    ];

    public function render($request, Throwable $exception): JsonResponse
    {
        return $this->errorResponse($exception->getMessage(), 400);
    }
}