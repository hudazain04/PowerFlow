<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;

//Route::get('/ping', function () {
//    return response()->json(['message' => 'pong']);
//
//
//});
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::prefix('system-admin')->middleware(['auth:api', 'role:system_admin'])->group(function () {
    // System Admin routes...
});

Route::prefix('service-provider')->middleware(['auth:api', 'role:service_provider_admin'])->group(function () {
    // Service Provider Admin routes...
});

Route::prefix('employee')->middleware(['auth:api', 'role:employee'])->group(function () {
    // Employee routes...
});

Route::prefix('subscriber')->middleware(['auth:api', 'role:subscriber'])->group(function () {
    // Subscriber routes...
});
