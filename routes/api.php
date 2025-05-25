<?php


use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\FaqController;
use \App\Http\Controllers\User\VerificationController;
use \App\Http\Controllers\User\PasswordController;
use \App\Http\Controllers\SuperAdmin\GeneratorRequestController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanPriceController;
use App\Http\Controllers\SubscriptionRequestController;
use App\Http\Controllers\SuperAdminStatisticsController;
use Illuminate\Http\Request;
use \App\Http\Controllers\User\CustomerRequestController;
use \App\Http\Controllers\SuperAdmin\NeighborhoodController;
use \App\Http\Controllers\Admin\AreaController;
use \App\Http\Controllers\Admin\ElectricalBoxController;
//Route::get('/ping', function () {
//    return response()->json(['message' => 'pong']);
//
//
//});
//////////////////wanli Api's///////////////////////////////////////////////////////
///
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

Route::prefix('email')->middleware('auth:api')->group(function () {

    Route::post('/send-verification', [VerificationController::class, 'send'])
        ->name('verification.send');
    Route::post('/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->name('verification.verify');
    Route::post('/resend', [VerificationController::class, 'resend'])
        ->middleware('throttle:3,1')
        ->name('verification.resend');
});

Route::prefix('/password')->middleware('auth:api')->group(function (){
    Route::post('/request', [PasswordController::class, 'request'])  ;
    Route::post('/verify', [PasswordController::class, 'verify']) ;
    Route::post('/resend', [PasswordController::class, 'resend']) ;
    Route::post('/reset', [PasswordController::class, 'reset'])  ;
});

Route::post('request',[GeneratorRequestController::class,'store'])->middleware(['auth:api', 'role:user']);

Route::prefix('/gen')->middleware(['auth:api','role:super admin'])->group(function (){
    Route::post('approve/{id}',[GeneratorRequestController::class,'approve']);
    Route::post('reject/{id}',[GeneratorRequestController::class,'reject']);
    Route::get('get',[GeneratorRequestController::class,'pendingRequests']);
});

Route::prefix('customer')->middleware('auth:api')->group(function (){
    Route::post('request',[CustomerRequestController::class,'store']);

});
Route::prefix('customer')->middleware(['auth:api'])->group(function () {
Route::post('approve/{id}',[CustomerRequestController::class,'approveRequest']);
    Route::post('reject/{id}',[CustomerRequestController::class,'rejectRequest']);
});

//super admin
Route::prefix('neighborhood')->middleware(['auth:api'])->group(function () {
    Route::post('store', [NeighborhoodController::class, 'store']);
    Route::get('all', [NeighborhoodController::class, 'index']);
    Route::get('show/{id}', [NeighborhoodController::class, 'show']);
});

// Generator Admin routes
Route::middleware(['auth:api'])->prefix('generator')->group(function () {
    // Areas
    Route::post('areas', [AreaController::class, 'store']);
    Route::post('areas/assign-box', [AreaController::class, 'assignBox']);
    Route::get('generator/areas', [AreaController::class, 'index']);
    Route::get('generator/areas/{id}/boxes', [AreaController::class, 'boxes']);




    // Boxes

    Route::post('boxes', [ElectricalBoxController::class, 'store']);
    Route::post('boxes/assign-counter', [ElectricalBoxController::class, 'assignCounter']);
    Route::get('generator/boxes/{id}/counters', [ElectricalBoxController::class, 'counters']);
    Route::get('generator/boxes/available', [ElectricalBoxController::class, 'available']);
});













//////////////////////////////////Huda Api's///////////////////////////////////////////////////////////////
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





Route::prefix('employee')->middleware(['auth:api', 'role:employee'])->group(function () {

});

Route::prefix('user')->middleware(['auth:api', 'role:user'])->group(function () {

Route::prefix('superAdminStatistics')->group(function (){
   Route::get('homeStatistics',[SuperAdminStatisticsController::class,'homeStatistics']);
   Route::get('getSubscriptionDistributionByPlan/{year}',[SuperAdminStatisticsController::class,'getSubscriptionDistributionByPlan']);
   Route::get('subscriptionsPerPlans',[SuperAdminStatisticsController::class,'subscriptionsPerPlans']);
   Route::get('subscriptionRequestsPerPlans',[SuperAdminStatisticsController::class,'subscriptionRequestsPerPlans']);
   Route::get('topRequestedPlan',[SuperAdminStatisticsController::class,'topRequestedPlan']);


});});

Route::prefix('subscriptionRequest')->group(function (){
   Route::get('getLastFive',[SubscriptionRequestController::class,'getLastFive']);
});

