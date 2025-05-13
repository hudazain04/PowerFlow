<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\FaqController;
use \App\Http\Controllers\User\VerificationController;
use \App\Http\Controllers\User\PasswordController;

//Route::get('/ping', function () {
//    return response()->json(['message' => 'pong']);
//
//
//});
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']) ;
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});


Route::prefix('faq')->middleware(['auth:api', 'role:super admin'])->group(function () {
    Route::put('/update/{id}',[FaqController::class,'updateFaq']);
    Route::delete('delete/{id}',[FaqController::class,'deleteFaq']);
    Route::post('/store',[FaqController::class,'createFaq']);
    Route::get('get/{category}',[FaqController::class,'getFaqByRole']);
});


Route::prefix('email')->middleware('auth:api')->group(function (){

    Route::post('/send-verification', [VerificationController::class, 'send'])
        ->name('verification.send');
    Route::post('/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->name('verification.verify');
    Route::post('/resend', [VerificationController::class, 'resend'])
        ->middleware( 'throttle:3,1')
        ->name('verification.resend');

});



Route::prefix('/password')->middleware('auth:api')->group(function (){
    Route::post('/request', [PasswordController::class, 'request'])  ;
    Route::post('/verify', [PasswordController::class, 'verify']) ;
    Route::post('/resend', [PasswordController::class, 'resend']) ;
    Route::post('/reset', [PasswordController::class, 'reset'])  ;
});







Route::prefix('power_generator')->middleware(['auth:api', 'role:admin'])->group(function () {

});

Route::prefix('employee')->middleware(['auth:api', 'role:employee'])->group(function () {

});

Route::prefix('user')->middleware(['auth:api', 'role:user'])->group(function () {

});


