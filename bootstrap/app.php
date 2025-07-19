<?php

use App\ApiHelper\ApiCode;
//use App\Http\Middleware\ExceptionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->api();
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'block'=>\App\Http\Middleware\BlockMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\App\Exceptions\ErrorException $e, Request $request) {
            return response()->json(['message'=> $e->message,'data'=>$e->data],$e->errorCode);
        });
        $exceptions->render(function (Exception $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage()
//                    'message' => __('messages.error.server_error')
            ], ApiCode::INTERNAL_SERVER_ERROR);

        });
    })

    ->create();

//        $middleware->alias([
//        ]);
//    })
