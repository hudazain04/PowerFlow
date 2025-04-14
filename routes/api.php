<?php

use App\Http\Controllers\FeatureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('feature')->group(function (){
   Route::get('getAll',[FeatureController::class,'index']);
    Route::get('findById/{id}',[FeatureController::class,'findById']);
    Route::post('create',[FeatureController::class,'store']);
    Route::patch('update/{id}',[FeatureController::class,'update']);
    Route::delete('delete/{id}',[FeatureController::class,'delete']);

});
