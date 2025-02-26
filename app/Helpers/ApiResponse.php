<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
class ApiResponse
{
    /**
     * Success Response
     */
    public static function success($data = [], string $message = "Request was successful", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Failure Response
     */
    public static function failure(string $message = "Something went wrong", int $statusCode = Response::HTTP_BAD_REQUEST, $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
