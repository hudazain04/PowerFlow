<?php

use \App\Http\Controllers\AuthController;
use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanPriceController;
use App\Http\Controllers\PowerGeneratorController;
use App\Http\Controllers\SubscriptionRequestController;
use App\Http\Controllers\SuperAdminStatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




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
    Route::post('create/{plan_id}',[PlanPriceController::class,'store']);
    Route::patch('update/{id}',[PlanPriceController::class,'update']);
    Route::delete('delete/{id}',[PlanPriceController::class,'delete']);

});

Route::prefix('plan')->group(function (){
    Route::get('getAll',[PlanController::class,'index']);
    Route::get('findById/{id}',[PlanController::class,'findById']);
    Route::post('create',[PlanController::class,'store']);
    Route::patch('update/{id}',[PlanController::class,'update']);
    Route::delete('delete/{id}',[PlanController::class,'delete']);
    Route::post('addFeature',[PlanController::class,'addFeature']);
    Route::delete('deleteFeature/{id}',[PlanController::class,'deleteFeature']);
    Route::patch('updateFeature/{id}',[PlanController::class,'updateFeature']);


});


Route::prefix('superAdminStatistics')->group(function (){
   Route::get('homeStatistics',[SuperAdminStatisticsController::class,'homeStatistics']);
   Route::get('getSubscriptionDistributionByPlan/{year}',[SuperAdminStatisticsController::class,'getSubscriptionDistributionByPlan']);
   Route::get('subscriptionsPerPlans',[SuperAdminStatisticsController::class,'subscriptionsPerPlans']);
   Route::get('subscriptionRequestsPerPlans',[SuperAdminStatisticsController::class,'subscriptionRequestsPerPlans']);
   Route::get('topRequestedPlan',[SuperAdminStatisticsController::class,'topRequestedPlan']);
   Route::get('getTotalVisitors',[SuperAdminStatisticsController::class,'getTotalVisitors']);
   Route::get('getAvgDailyVisits',[SuperAdminStatisticsController::class,'getAvgDailyVisits']);
   Route::get('planStatistics/{plan_id}',[SuperAdminStatisticsController::class,'planStatistics']);
   Route::get('distributionOfPlanPricesRequests/{plan_id}',[SuperAdminStatisticsController::class,'distributionOfPlanPricesRequests']);
});


Route::prefix('subscriptionRequest')->group(function (){
   Route::get('getLastFive',[SubscriptionRequestController::class,'getLastFive']);
});


Route::prefix('AppInfo')->group(function (){
   Route::post('createAboutApp',[AppInfoController::class,'createAboutApp']);
   Route::patch('updateAboutApp',[AppInfoController::class,'updateAboutApp']);
   Route::get('getAboutApp',[AppInfoController::class,'getAboutApp']);
   Route::delete('deleteAboutApp',[AppInfoController::class,'deleteAboutApp']);
    Route::post('createTermsAndConditions',[AppInfoController::class,'createTermsAndConditions']);
    Route::patch('updateTermsAndConditions',[AppInfoController::class,'updateTermsAndConditions']);
    Route::get('getTermsAndConditions',[AppInfoController::class,'getTermsAndConditions']);
    Route::delete('deleteTermsAndConditions',[AppInfoController::class,'deleteTermsAndConditions']);
    Route::post('createPrivacyPolicy',[AppInfoController::class,'createPrivacyPolicy']);
    Route::patch('updatePrivacyPolicy',[AppInfoController::class,'updatePrivacyPolicy']);
    Route::get('getPrivacyPolicy',[AppInfoController::class,'getPrivacyPolicy']);
    Route::delete('deletePrivacyPolicy',[AppInfoController::class,'deletePrivacyPolicy']);

});
Route::prefix('powerGenerator')->group(function (){
    Route::get('getForPlan/{id}',[PowerGeneratorController::class,'getForPlan']);
});

Route::get('visitLandingPage',[SuperAdminStatisticsController::class,'visitLandingPage']);
