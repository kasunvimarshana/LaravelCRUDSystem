<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait
{
    /**
     * Success response.
     *
     * @param array|object|null $data
     * @param string $message
     *
     * @return JsonResponse
     */
    public function responseSuccess($data, $message = "Successful", int $responseCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null
        ], $responseCode);
    }

    /**
     * Error response.
     *
     * @param array|object|null $errors
     * @param string $message
     * @param int $responseCode
     *
     * @return JsonResponse
     */
    public function responseError($errors, $message = "Something went wrong.", int $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors
        ], $responseCode);
    }
}
