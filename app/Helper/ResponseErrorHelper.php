<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;

abstract class ResponseErrorHelper
{
    public static function json(string $message, ?int $code = 200): JsonResponse
    {
        return new JsonResponse(
            [
                'error' => true,
                'message' => $message,
                'code' => $code
            ],
            $code
        );
    }
}
