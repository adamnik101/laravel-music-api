<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseAPI
{
    public function coreResponse(string $message, $data, int $statusCode, bool $isSuccess = true) : JsonResponse
    {
        if(!$message) return response()->json(['message' => 'Message is required'])->setStatusCode(500);

        if($isSuccess) {
           return response()
               ->json([
                   "message" => $message,
                   "data" => $data,
                   "error" => false,
                   "status_code" => $statusCode
               ])
               ->setStatusCode($statusCode);
        }

        return response()
            ->json([
                "message" => $message,
                "errors" => $data,
                "error" => true,
                "status_code" => $statusCode
            ])
            ->setStatusCode($statusCode);
    }

    public function success(string $message, $data, int $statusCode = 200): JsonResponse
    {
        return $this->coreResponse($message, $data, $statusCode);
    }
    public function error(string $message, int $statusCode, array $errors = [], $isSuccess = false): JsonResponse
    {
        return $this->coreResponse($message, $errors, $statusCode, $isSuccess);
    }
}
