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

    public  function successWithPagination($data = null,?array $extraData=[], $message = 'Success', $code = ApiCode::OK): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'extraData'=>$extraData,
            'meta'    => [
                'current_page' => $data->resource->currentPage(),
                'last_page'    => $data->resource->lastPage(),
                'per_page'     => $data->resource->perPage(),
                'total'        => $data->resource->total(),
            ],
            'links'   => [
                'first' => $data->resource->url(1),
                'last'  => $data->resource->url($data->resource->lastPage()),
                'prev'  => $data->resource->previousPageUrl(),
                'next'  => $data->resource->nextPageUrl(),
            ]
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
