<?php

namespace App\ApiHelper;
use Illuminate\Http\JsonResponse;
use PhpParser\Builder\Trait_;

trait ApiResponse
{

    public function success($data = null, $message = 'Success', $code = ApiCode::OK): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function error($message = 'An error occurred', $errorCode = ApiCode::INTERNAL_SERVER_ERROR, $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $errorCode);
    }
    public function serverError(): JsonResponse
    {
        return response()->json([
            'message' => __('messages.error.server_error'),
        ], ApiCode::INTERNAL_SERVER_ERROR);
    }

}
