<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\PowerGeneratorController;
use App\Http\Controllers\SuperAdmin\AppInfoController;
use App\Http\Controllers\SuperAdmin\FaqController;
use App\Http\Controllers\SuperAdmin\FeatureController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\PlanPriceController;
use App\Http\Controllers\SuperAdmin\SubscriptionRequestController;
use App\Http\Controllers\SuperAdmin\SuperAdminStatisticsController;
use App\Http\Controllers\User\complaintcontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\User\VerificationController;
use \App\Http\Controllers\User\PasswordController;
use \App\Http\Controllers\SuperAdmin\GeneratorRequestController;
use \App\Http\Controllers\User\CustomerRequestController;
use \App\Http\Controllers\SuperAdmin\NeighborhoodController;
use \App\Http\Controllers\Admin\AreaController;
use \App\Http\Controllers\Admin\ElectricalBoxController;
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
});

Route::prefix('planPrice')->group(function (){
    Route::get('getAll/{plan_id}',[PlanPriceController::class,'index']);
    Route::get('findById/{id}',[PlanPriceController::class,'findById']);

});

Route::prefix('plan')->group(function (){
    Route::get('getAll',[PlanController::class,'index']);
    Route::get('findById/{id}',[PlanController::class,'findById']);

});



Route::middleware('auth:api')->group(function (){
    Route::prefix('feature')->group(function (){
        Route::post('create',[FeatureController::class,'store'])->middleware('role:super admin');
        Route::patch('update/{id}',[FeatureController::class,'update'])->middleware('role:super admin');
        Route::delete('delete/{id}',[FeatureController::class,'delete'])->middleware('role:super admin');

    });

    Route::prefix('planPrice')->group(function (){
        Route::post('create/{plan_id}',[PlanPriceController::class,'store'])->middleware('role:super admin');
        Route::patch('update/{id}',[PlanPriceController::class,'update'])->middleware('role:super admin');
        Route::delete('delete/{id}',[PlanPriceController::class,'delete'])->middleware('role:super admin');

    });

    Route::prefix('plan')->group(function (){
        Route::post('create',[PlanController::class,'store'])->middleware('role:super admin');
        Route::patch('update/{id}',[PlanController::class,'update'])->middleware('role:super admin');
        Route::delete('delete/{id}',[PlanController::class,'delete'])->middleware('role:super admin');
        Route::post('addFeature',[PlanController::class,'addFeature'])->middleware('role:super admin');
        Route::delete('deleteFeature/{id}',[PlanController::class,'deleteFeature'])->middleware('role:super admin');
        Route::patch('updateFeature/{id}',[PlanController::class,'updateFeature'])->middleware('role:super admin');



    });


    Route::prefix('superAdminStatistics')->middleware('role:super admin')->group(function (){
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



    Route::prefix('AppInfo')->group(function (){
        Route::middleware('role:super admin')->group(function (){
            Route::post('createAboutApp',[AppInfoController::class,'createAboutApp']);
            Route::patch('updateAboutApp',[AppInfoController::class,'updateAboutApp']);
            Route::delete('deleteAboutApp',[AppInfoController::class,'deleteAboutApp']);
            Route::post('createTermsAndConditions',[AppInfoController::class,'createTermsAndConditions']);
            Route::patch('updateTermsAndConditions',[AppInfoController::class,'updateTermsAndConditions']);
            Route::delete('deleteTermsAndConditions',[AppInfoController::class,'deleteTermsAndConditions']);
            Route::post('createPrivacyPolicy',[AppInfoController::class,'createPrivacyPolicy']);
            Route::patch('updatePrivacyPolicy',[AppInfoController::class,'updatePrivacyPolicy']);
            Route::delete('deletePrivacyPolicy',[AppInfoController::class,'deletePrivacyPolicy']);

        });
        Route::get('getAboutApp',[AppInfoController::class,'getAboutApp']);
        Route::get('getTermsAndConditions',[AppInfoController::class,'getTermsAndConditions']);
        Route::get('getPrivacyPolicy',[AppInfoController::class,'getPrivacyPolicy']);

    });
    Route::prefix('powerGenerator')->group(function (){
        Route::get('getForPlan/{id}',[PowerGeneratorController::class,'getForPlan'])->middleware('role:super admin');
    });

    Route::prefix('complaint')->group(function (){
        Route::post('createCutComplaint',[ComplaintController::class,'createCutComplaint'])->middleware('block');
        Route::patch('updateCutComplaint/{complaint_id}',[complaintcontroller::class,'updateCutComplaint'])->middleware('role:employee');
        Route::post('createComplaint',[complaintcontroller::class,'createComplaint']);
        Route::delete('deleteComplaint/{complaint_id}',[complaintcontroller::class,'deleteComplaint']);
        Route::get('getComplaints',[complaintcontroller::class,'getComplaints']);
    });

    Route::prefix('subscriptionRequest')->group(function (){
        Route::get('getLastFive',[SubscriptionRequestController::class,'getLastFive'])->middleware('role:super admin');
    });

    Route::prefix('account')->group(function (){
        Route::get('blocking/{id}',[AccountController::class,'blocking'])->middleware('role:super admin');
    });

});






Route::prefix('employee')->middleware(['auth:api', 'role:employee'])->group(function () {

});

Route::prefix('user')->middleware(['auth:api', 'role:user'])->group(function () {



});







Route::get('visitLandingPage',[SuperAdminStatisticsController::class,'visitLandingPage']);
