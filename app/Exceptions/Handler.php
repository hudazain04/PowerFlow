<?php

namespace App\Exceptions;

use App\ApiHelper\ApiCode;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException && $exception->getPrevious() instanceof TokenExpiredException) {
            return response()->json(['message' => 'Token has expired'], 401);
        }

        if ($exception instanceof UnauthorizedHttpException && $exception->getPrevious() instanceof TokenInvalidException) {
            return response()->json(['message' => 'Token is invalid'], 401);
        }

        if ($exception instanceof UnauthorizedHttpException && $exception->getPrevious() instanceof JWTException) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if ($exception instanceof ErrorException) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => $exception->data ?? null
            ], $exception->errorCode ?? ApiCode::INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof \Exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], ApiCode::INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $exception);
    }

//    public function register(): void
//    {
//        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $e, $request) {
//            $previous = $e->getPrevious();
//
//            if ($previous instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
//                return response()->json([
//                    'message' => 'Token has expired'
//                ], 401);
//            }
//
//            if ($previous instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
//                return response()->json([
//                    'message' => 'Token is invalid'
//                ], 401);
//            }
//
//            if ($previous instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
//                return response()->json([
//                    'message' => 'Token not provided'
//                ], 401);
//            }
//
//            return response()->json([
//                'message' => 'Unauthenticated (wrapped UnauthorizedHttpException)'
//            ], 401);
//        });
//
//        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
//            return response()->json([
//                'message' => 'Unauthenticated (auth exception)'
//            ], 401);
//        });
//
//        // Catch any ErrorException you throw manually
//        $this->renderable(function (ErrorException $e, $request) {
//            return response()->json([
//                'message' => $e->getMessage(),
//                'data' => $e->data ?? null
//            ], $e->errorCode ?? 500);
//        });
//
//        // Generic fallback
//        $this->renderable(function (\Throwable $e, $request) {
//            return response()->json([
//                'message' => $e->getMessage(),
//            ], ApiCode::INTERNAL_SERVER_ERROR);
//        });
//    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'message' => __('messages.error.unauthenticated')
        ], ApiCode::UNAUTHORIZED);
    }
}
