<?php

use App\Http\Controllers\FeatureController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanPriceController;
use App\Http\Controllers\SubscriptionRequestController;
use App\Http\Controllers\SuperAdminStatisticsController;
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

Route::prefix('planPrice')->group(function (){
    Route::get('getAll/{plan_id}',[PlanPriceController::class,'index']);
    Route::get('findById/{id}',[PlanPriceController::class,'findById']);
    Route::post('create',[PlanPriceController::class,'store']);
    Route::patch('update/{id}',[PlanPriceController::class,'update']);
    Route::delete('delete/{id}',[PlanPriceController::class,'delete']);

});

Route::prefix('plan')->group(function (){
    Route::get('getAll',[PlanController::class,'index']);
    Route::get('findById/{id}',[PlanController::class,'findById']);
    Route::post('create',[PlanController::class,'store']);
    Route::patch('update/{id}',[PlanController::class,'update']);
    Route::delete('delete/{id}',[PlanController::class,'delete']);

});


Route::prefix('superAdminStatistics')->group(function (){
   Route::get('homeStatistics',[SuperAdminStatisticsController::class,'homeStatistics']);
   Route::get('getSubscriptionDistributionByPlan/{year}',[SuperAdminStatisticsController::class,'getSubscriptionDistributionByPlan']);
   Route::get('subscriptionsPerPlans',[SuperAdminStatisticsController::class,'subscriptionsPerPlans']);
   Route::get('subscriptionRequestsPerPlans',[SuperAdminStatisticsController::class,'subscriptionRequestsPerPlans']);
   Route::get('topRequestedPlan',[SuperAdminStatisticsController::class,'topRequestedPlan']);

});


Route::prefix('subscriptionRequest')->group(function (){
   Route::get('getLastFive',[SubscriptionRequestController::class,'getLastFive']);
});
