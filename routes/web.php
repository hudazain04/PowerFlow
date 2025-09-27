<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');



});
Route::get('/admin/full-data', [\App\Http\Controllers\User\UserAppController::class, 'getFullData'])

    ->name('dashboard');
