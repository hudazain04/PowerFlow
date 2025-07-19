<?php


use App\Http\Controllers\Admin\AreaBoxController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\CounterBoxController;
use App\Http\Controllers\Admin\ElectricalBoxController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanPriceController;
use App\Http\Controllers\PowerGeneratorController;
use App\Http\Controllers\SubscriptionRequestController;
use App\Http\Controllers\SuperAdmin\GeneratorRequestController;
use App\Http\Controllers\SuperAdmin\NeighborhoodController;
use App\Http\Controllers\SuperAdminStatisticsController;
use App\Http\Controllers\User\CustomerRequestController;
use App\Http\Controllers\User\PasswordController;
use App\Http\Controllers\User\VerificationController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']) ;
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('faq')->group(function () {
        Route::middleware('role:super admin')->group(function () {
            Route::put('/update/{id}', [FaqController::class, 'updateFaq']);
            Route::delete('delete/{id}', [FaqController::class, 'deleteFaq']);
            Route::post('/store', [FaqController::class, 'createFaq']);
        });
        Route::get('get/{category}', [FaqController::class, 'getFaqByRole']);
    });
    Route::prefix('email')->group(function () {

        Route::post('/send-verification', [VerificationController::class, 'send'])
            ->name('verification.send');
        Route::post('/verify/{id}/{hash}', [VerificationController::class, 'verify'])
            ->name('verification.verify');
        Route::post('/resend', [VerificationController::class, 'resend'])
            ->middleware('throttle:3,1')
            ->name('verification.resend');
    });

    Route::prefix('/password')->group(function () {
        Route::post('/request', [PasswordController::class, 'request']);
        Route::post('/verify', [PasswordController::class, 'verify']);
        Route::post('/resend', [PasswordController::class, 'resend']);
        Route::post('/reset', [PasswordController::class, 'reset']);
    });

    Route::post('request', [GeneratorRequestController::class, 'store'])->middleware('role:user');
    Route::prefix('/gen')->middleware('role:super admin')->group(function () {
        Route::post('approve/{id}', [GeneratorRequestController::class, 'approve']);
        Route::post('reject/{id}', [GeneratorRequestController::class, 'reject']);
        Route::get('get', [GeneratorRequestController::class, 'pendingRequests']);
    });

    Route::prefix('customer')->group(function () {
        Route::post('request', [CustomerRequestController::class, 'store']);
        Route::post('approve/{id}', [CustomerRequestController::class, 'approveRequest']);
        Route::post('reject/{id}', [CustomerRequestController::class, 'rejectRequest']);

    });
    Route::prefix('neighborhood')->middleware('role:super admin')->group(function () {
        Route::post('store', [NeighborhoodController::class, 'store']);
        Route::get('all', [NeighborhoodController::class, 'index']);
        Route::get('show/{id}', [NeighborhoodController::class, 'show']);
    });

    Route::prefix('generator')->group(function () {
        // Areas//////
        Route::post('areas', [AreaController::class, 'store']);
        Route::get('getareas', [AreaController::class, 'index']);

        // Box assignment to areas////
        Route::post('/areas/{area_id}/boxes', [AreaBoxController::class, 'assignBox']);
        Route::delete('/areas/{area}/boxes/{box}', [AreaBoxController::class, 'removeBoxFromArea']);
        Route::get('/areas/{area_id}/boxes/available', [AreaBoxController::class, 'getAvailableBoxes']);
        Route::get('/areas/{area_id}/boxes', [AreaBoxController::class, 'getAreaBoxes']);

// Box management////////

        Route::post('/boxes', [ElectricalBoxController::class, 'store']);

// counter with boxes assignment///////
        Route::post('/counters/assign-box', [CounterBoxController::class, 'assignCounter']);
        Route::get('/boxes/{box_id}/counters', [CounterBoxController::class, 'getBoxCounters']);
        Route::get('/counters/{counter_id}/current-box', [CounterBoxController::class, 'getCurrentCounter']);
        Route::delete('/counters/remove-box', [CounterBoxController::class, 'removeCounter']);
        // employee creation/////////
        Route::post('/createEmp', [EmployeeController::class, 'create']);
        Route::patch('/updateEmp/{id}', [EmployeeController::class, 'update']);
        Route::delete('/deleteEmp/{id}', [EmployeeController::class, 'delete']);
        Route::get('/getEmps/{generator_id}', [EmployeeController::class, 'getEmployees']);
        Route::get('/getEmp/{id}', [EmployeeController::class, 'getEmployee']);


    });


    Route::middleware('role:super admin')->group(function () {
        Route::prefix('feature')->group(function () {
            Route::get('getAll', [FeatureController::class, 'index']);
            Route::get('findById/{id}', [FeatureController::class, 'findById']);
            Route::post('create', [FeatureController::class, 'store']);
            Route::patch('update/{id}', [FeatureController::class, 'update']);
            Route::delete('delete/{id}', [FeatureController::class, 'delete']);

        });

        Route::prefix('planPrice')->group(function () {
            Route::post('create/{plan_id}', [PlanPriceController::class, 'store']);
            Route::patch('update/{id}', [PlanPriceController::class, 'update']);
            Route::delete('delete/{id}', [PlanPriceController::class, 'delete']);

        });

        Route::prefix('plan')->group(function () {
            Route::post('create', [PlanController::class, 'store']);
            Route::patch('update/{id}', [PlanController::class, 'update']);
            Route::delete('delete/{id}', [PlanController::class, 'delete']);
            Route::post('addFeature', [PlanController::class, 'addFeature']);
            Route::delete('deleteFeature/{id}', [PlanController::class, 'deleteFeature']);
            Route::patch('updateFeature/{id}', [PlanController::class, 'updateFeature']);
        });

        Route::prefix('superAdminStatistics')->group(function () {
            Route::get('homeStatistics', [SuperAdminStatisticsController::class, 'homeStatistics']);
            Route::get('getSubscriptionDistributionByPlan/{year}', [SuperAdminStatisticsController::class, 'getSubscriptionDistributionByPlan']);
            Route::get('subscriptionsPerPlans', [SuperAdminStatisticsController::class, 'subscriptionsPerPlans']);
            Route::get('subscriptionRequestsPerPlans', [SuperAdminStatisticsController::class, 'subscriptionRequestsPerPlans']);
            Route::get('topRequestedPlan', [SuperAdminStatisticsController::class, 'topRequestedPlan']);

            Route::get('getTotalVisitors', [SuperAdminStatisticsController::class, 'getTotalVisitors']);
            Route::get('getAvgDailyVisits', [SuperAdminStatisticsController::class, 'getAvgDailyVisits']);
            Route::get('planStatistics/{plan_id}', [SuperAdminStatisticsController::class, 'planStatistics']);
            Route::get('distributionOfPlanPricesRequests/{plan_id}', [SuperAdminStatisticsController::class, 'distributionOfPlanPricesRequests']);
        });

        Route::prefix('subscriptionRequest')->group(function () {
            Route::get('getLastFive', [SubscriptionRequestController::class, 'getLastFive']);
        });

        Route::prefix('AppInfo')->group(function () {
            Route::post('createAboutApp', [AppInfoController::class, 'createAboutApp']);
            Route::patch('updateAboutApp', [AppInfoController::class, 'updateAboutApp']);
            Route::delete('deleteAboutApp', [AppInfoController::class, 'deleteAboutApp']);
            Route::post('createTermsAndConditions', [AppInfoController::class, 'createTermsAndConditions']);
            Route::patch('updateTermsAndConditions', [AppInfoController::class, 'updateTermsAndConditions']);
            Route::delete('deleteTermsAndConditions', [AppInfoController::class, 'deleteTermsAndConditions']);
            Route::post('createPrivacyPolicy', [AppInfoController::class, 'createPrivacyPolicy']);
            Route::patch('updatePrivacyPolicy', [AppInfoController::class, 'updatePrivacyPolicy']);
            Route::delete('deletePrivacyPolicy', [AppInfoController::class, 'deletePrivacyPolicy']);

        });

    });

    Route::prefix('planPrice')->group(function () {
        Route::get('getAll/{plan_id}', [PlanPriceController::class, 'index']);
        Route::get('findById/{id}', [PlanPriceController::class, 'findById']);
    });

    Route::prefix('plan')->group(function () {
        Route::get('getAll', [PlanController::class, 'index']);
        Route::get('findById/{id}', [PlanController::class, 'findById']);
    });


    Route::prefix('employee')->middleware(['auth:api', 'role:employee'])->group(function () {

    });

    Route::prefix('user')->middleware(['auth:api', 'role:user'])->group(function () {


    });

    Route::prefix('AppInfo')->group(function () {
        Route::get('getAboutApp',[AppInfoController::class,'getAboutApp']);
        Route::get('getTermsAndConditions',[AppInfoController::class,'getTermsAndConditions']);
        Route::get('getPrivacyPolicy',[AppInfoController::class,'getPrivacyPolicy']);

    });
});

Route::prefix('powerGenerator')->group(function (){
    Route::get('getForPlan/{id}',[PowerGeneratorController::class,'getForPlan']);
});

Route::get('visitLandingPage',[SuperAdminStatisticsController::class,'visitLandingPage']);


