<?php

namespace App\ApiHelper;
use Illuminate\Http\JsonResponse;

class ApiResponse
{

    public static function success($data = null, $message = 'Success', $code = ApiCode::OK): JsonResponse
    {
        return response()->json([

            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function error($message = 'An error occurred', $errorCode = ApiCode::INTERNAL_SERVER_ERROR, $data = null): JsonResponse
    {
        return response()->json([

            'code' => $errorCode,
            'message' => $message,
            'data' => $data,
        ], $errorCode);
    }

}
