<?php

namespace App\ApiHelper;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{

    public function success($data = null, $message = 'Success', $code = ApiCode::OK): JsonResponse
    {
        return response()->json([

            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function error($message = 'An error occurred', $errorCode = ApiCode::INTERNAL_SERVER_ERROR, $data = null): JsonResponse
    {
        return response()->json([

            'code' => $errorCode,
            'message' => $message,
            'data' => $data,
        ], $errorCode);
    }
    public function serverError(): JsonResponse
    {
        return response()->json([

            'code' => ApiCode::INTERNAL_SERVER_ERROR,
            'message' => 'Server error occurred',
        ], ApiCode::INTERNAL_SERVER_ERROR);
    }

}
