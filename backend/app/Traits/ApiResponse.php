<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse(mixed $data = null, string $message = '', int $statusCode = 200): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function errorResponse(string $message = '', int $statusCode = 400, mixed $data = null): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}