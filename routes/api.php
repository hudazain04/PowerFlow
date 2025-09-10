<?php


use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AreaBoxController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\CounterBoxController;
use App\Http\Controllers\Admin\ElectricalBoxController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PowerGeneratorController;
use App\Http\Controllers\Admin\SpendingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\SpendingPaymentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SuperAdmin\AppInfoController;
use App\Http\Controllers\SuperAdmin\FaqController;
use App\Http\Controllers\SuperAdmin\FeatureController;
use App\Http\Controllers\SuperAdmin\GeneratorRequestController;
use App\Http\Controllers\SuperAdmin\NeighborhoodController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\PlanPriceController;
use App\Http\Controllers\SuperAdmin\SubscriptionRequestController;
use App\Http\Controllers\SuperAdmin\SuperAdminStatisticsController;
use App\Http\Controllers\User\complaintcontroller;
use App\Http\Controllers\User\CustomerRequestController;
use App\Http\Controllers\User\PasswordController;
use \App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\User\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employee\EmployeeAuthController;
use App\Http\Controllers\User\UserAppController;



    Route::middleware('lang')->group(function () {
        // Authentication routes
        Route::prefix('auth')->group(function () {
            Route::post('register', [AuthController::class, 'register']);
            Route::post('login', [AuthController::class, 'login']);
            Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        });
        Route::prefix('employee')->group(function () {
            Route::post('login', [EmployeeAuthController::class, 'login']);
            Route::middleware('auth:employee')->group(function () {
                Route::post('logout', [EmployeeAuthController::class, 'logout']);
                Route::get('permissions/{id}',[EmployeeAuthController::class,'getPermissions']);
            });
        });
        Route::prefix('user')->group(function (){
            Route::put('name/{id}',[UserAppController::class,'name']);
            Route::put('password/{id}',[UserAppController::class,'resetPassword']);
            Route::get('counters/{id}',[UserAppController::class,'getCounters']);
            Route::get('counter/{id}',[UserAppController::class,'getCounter']);
            Route::get('payments/{counter_id}',[UserAppController::class,'getPayments']);
            Route::get('/pdf/{counter_id}', [UserAppController::class, 'downloadPaymentsPdf']);
            Route::get('spending_consumption/{counter_id}',[UserAppController::class,'spendingConsumption']);
            Route::get('generators/nearby', [UserAppController::class, 'findNearbyGenerators']);

        });

        // Email verification routes
        Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify'])
            ->name('verification.verify');

        Route::prefix('email')->group(function () {
            Route::post('/send-verification', [VerificationController::class, 'send'])
                ->name('verification.send');
            Route::post('/resend', [VerificationController::class, 'resend'])
                ->middleware('throttle:3,1')
                ->name('verification.resend');
        });

        // Password reset routes
        Route::prefix('/password')->group(function () {
            Route::post('/request', [PasswordController::class, 'request']);
            Route::post('/resend', [PasswordController::class, 'resend'])->middleware('throttle:3,1');
            Route::post('/reset', [PasswordController::class, 'reset']);
            Route::get('/verify', [PasswordController::class, 'verify'])->name('verification.pass');
        });

        // Public info routes
        Route::prefix('AppInfo')->group(function () {
            Route::get('getAboutApp', [AppInfoController::class, 'getAboutApp']);
            Route::get('getTermsAndConditions', [AppInfoController::class, 'getTermsAndConditions']);
            Route::get('getPrivacyPolicy', [AppInfoController::class, 'getPrivacyPolicy']);
        });

        // Public plan routes
        Route::prefix('plan')->group(function () {
            Route::get('getAll', [PlanController::class, 'index']);
            Route::get('findById/{id}', [PlanController::class, 'findById']);
        });

        Route::prefix('planPrice')->group(function () {
            Route::get('getAll/{plan_id}', [PlanPriceController::class, 'index']);
            Route::get('findById/{id}', [PlanPriceController::class, 'findById']);
        });



        Route::get('visitLandingPage', [SuperAdminStatisticsController::class, 'visitLandingPage']);


    });

    // Protected routes
    Route::middleware(['auth:api', 'lang'])->group(function () {

        Route::prefix('powerGenerator')->group(function () {
            Route::get('getForPlan/{id}', [PowerGeneratorController::class, 'getForPlan'])
                ->middleware('permission:VIEW_POWER_GENERATORS');
            Route::get('getAll', [PowerGeneratorController::class, 'getAll'])
                ->middleware('permission:VIEW_POWER_GENERATORS');
            Route::get('getLastSubscription/{id}', [SubscriptionController::class, 'getLastSubscription'])
                ->middleware('permission:VIEW_SUBSCRIPTIONS');
        });
        // Generator management routes
        Route::prefix('generator')->group(function () {
            // Areas
            Route::post('areas', [AreaController::class, 'store'])
                ->middleware('permission:CREATE_AREAS');
            Route::get('getArea/{id}',[AreaController::class,'getArea'])
                ->middleware('permission:VIEW_AREA');
            Route::get('getAreas', [AreaController::class, 'index'])
                ->middleware('permission:VIEW_AREAS');
            Route::put('update/{id}', [AreaController::class, 'update'])
                ->middleware('permission:UPDATE_AREAS');
            Route::delete('delete', [AreaController::class, 'delete'])
                ->middleware('permission:DELETE_AREAS');


            // Box assignment to areas
            Route::post('/areas/{area_id}/boxes', [AreaBoxController::class, 'assignBox'])
                ->middleware('permission:ASSIGN_BOXES_TO_AREAS');
            Route::get('/areas/{area_id}/boxes/available', [AreaBoxController::class, 'getAvailableBoxes'])
                ->middleware('permission:VIEW_AREA_BOXES');
            Route::delete('/areas/{area}/boxes/{box}', [AreaBoxController::class, 'removeBoxFromArea'])
                ->middleware('permission:REMOVE_BOXES_FROM_AREAS');
            Route::get('/areas/{area_id}/boxes', [AreaBoxController::class, 'getAreaBoxes'])
                ->middleware('permission:VIEW_AREA_BOXES');


            // Box management
            Route::post('/boxes', [ElectricalBoxController::class, 'store'])
                ->middleware('permission:CREATE_BOXES');
            Route::get('/boxes/{id}', [ElectricalBoxController::class, 'get'])
                ->middleware('permission:VIEW_BOXES');
            Route::delete('/boxes', [ElectricalBoxController::class, 'destroy'])
                ->middleware('permission:DELETE_BOXES');
            Route::put('/box/update/{id}', [ElectricalBoxController::class, 'update'])
                ->middleware('permission:UPDATE_BOXES');

            Route::get('showBoxes/{id}', [ElectricalBoxController::class, 'show']);
            // Counter management
            Route::post('/counters', [CounterBoxController::class, 'create'])
                ->middleware('permission:CREATE_COUNTERS');
            Route::put('/counter/update/{id}', [CounterBoxController::class, 'update'])
                ->middleware('permission:UPDATE_COUNTERS');
            Route::delete('counters/{id}', [CounterBoxController::class, 'destroy'])
                ->middleware('permission:DELETE_COUNTERS');
            Route::get('/counters', [CounterController::class, 'get'])
                ->middleware('permission:VIEW_COUNTERS');

            // Counter-box assignment
            Route::get('/boxes/{box_id}/counters', [CounterBoxController::class, 'getBoxCounters'])
                ->middleware('permission:VIEW_BOX_COUNTERS');
            Route::get('/counters/{counter_id}/current-box', [CounterBoxController::class, 'getCurrentCounter'])
                ->middleware('permission:VIEW_COUNTER_CURRENT_BOX');
            Route::delete('/counters/remove-box', [CounterBoxController::class, 'removeCounter'])
                ->middleware('permission:REMOVE_COUNTER_FROM_BOX');

            // Employee management
            Route::post('/createEmp', [EmployeeController::class, 'create'])
                ->middleware('permission:CREATE_EMPLOYEES');
            Route::put('/updateEmp/{id}', [EmployeeController::class, 'update'])
                ->middleware('permission:UPDATE_EMPLOYEES');
            Route::delete('deleteEmp', [EmployeeController::class, 'delete'])
                ->middleware('permission:DELETE_EMPLOYEES');
            Route::get('/getEmps/{generator_id}', [EmployeeController::class, 'getEmployees'])
                ->middleware('permission:VIEW_EMPLOYEES');
            Route::get('/getEmp/{id}', [EmployeeController::class, 'getEmployee'])
                ->middleware('permission:VIEW_EMPLOYEE_DETAILS');

            Route::get('/permissions', [EmployeeController::class, 'getPermission']);
        });


        // FAQ routes
        Route::prefix('faq')->group(function () {
            Route::put('/update/{id}', [FaqController::class, 'updateFaq'])
                ->middleware('permission:UPDATE_FAQ');
            Route::delete('delete/{id}', [FaqController::class, 'deleteFaq'])
                ->middleware('permission:DELETE_FAQ');
            Route::post('/store', [FaqController::class, 'createFaq'])
                ->middleware('permission:CREATE_FAQ');
            Route::get('get/{category}', [FaqController::class, 'getFaqByRole'])
                ->middleware('permission:VIEW_FAQ');
        });

        // Generator request routes
        Route::post('request', [GeneratorRequestController::class, 'store'])
            ->middleware('permission:CREATE_GENERATOR_REQUEST');

        Route::prefix('/gen')->group(function () {
            Route::post('approve/{id}', [GeneratorRequestController::class, 'approve'])
                ->middleware('permission:APPROVE_GENERATOR_REQUEST');
            Route::post('reject/{id}', [GeneratorRequestController::class, 'reject'])
                ->middleware('permission:REJECT_GENERATOR_REQUEST');
            Route::get('get', [GeneratorRequestController::class, 'pendingRequests'])
                ->middleware('permission:VIEW_GENERATOR_REQUESTS');
            Route::delete('/generators/{id}', [GeneratorRequestController::class, 'delete'])
                ->middleware('permission:DELETE_GENERATOR');
        });

        // Customer request routes
        Route::prefix('customer')->group(function () {
            Route::post('request', [CustomerRequestController::class, 'store'])
                ->middleware('permission:CREATE_CUSTOMER_REQUEST');
            Route::post('approve/{id}', [CustomerRequestController::class, 'approveRequest'])
                ->middleware('permission:APPROVE_CUSTOMER_REQUEST');
            Route::post('reject/{id}', [CustomerRequestController::class, 'rejectRequest'])
                ->middleware('permission:REJECT_CUSTOMER_REQUEST');
            Route::get('getPending', [CustomerRequestController::class, 'pendingRequests'])
                ->middleware('permission:VIEW_CUSTOMER_REQUESTS');
        });

        // Neighborhood routes
        Route::prefix('neighborhood')->group(function () {
            Route::post('store', [NeighborhoodController::class, 'store'])
                ->middleware('permission:CREATE_NEIGHBORHOOD');
            Route::get('all', [NeighborhoodController::class, 'index'])
                ->middleware('permission:VIEW_NEIGHBORHOODS');
            Route::get('show/{id}', [NeighborhoodController::class, 'show'])
                ->middleware('permission:VIEW_NEIGHBORHOODS');
            Route::put('update/{id}', [NeighborhoodController::class, 'update'])
                ->middleware('permission:UPDATE_NEIGHBORHOOD');
            Route::delete('delete/{id}', [NeighborhoodController::class, 'delete'])
                ->middleware('permission:DELETE_NEIGHBORHOOD');
        });
        Route::middleware('role:superAdmin')->group(function () {
            Route::get('/generators/{generator}/statistics', [SuperAdminStatisticsController::class
                            , 'getGeneratorStatistics']);
            Route::get('/generators/{id}/info', [SuperAdminStatisticsController::class, 'getGenInfo']);
            Route::delete('/generators/{id}/', [GeneratorRequestController::class, 'delete']);
        });


        // Feature routes
        Route::prefix('feature')->group(function () {
            Route::get('getAll', [FeatureController::class, 'index'])
                ->middleware('permission:VIEW_FEATURES');
            Route::get('findById/{id}', [FeatureController::class, 'findById'])
                ->middleware('permission:VIEW_FEATURES');
            Route::post('create', [FeatureController::class, 'store'])
                ->middleware('permission:CREATE_FEATURE');
            Route::patch('update/{id}', [FeatureController::class, 'update'])
                ->middleware('permission:UPDATE_FEATURE');
            Route::delete('delete/{id}', [FeatureController::class, 'delete'])
                ->middleware('permission:DELETE_FEATURE');
        });

        // Plan price routes
        Route::prefix('planPrice')->group(function () {
            Route::post('create/{plan_id}', [PlanPriceController::class, 'store'])
                ->middleware('permission:CREATE_PLAN_PRICE');
            Route::patch('update/{id}', [PlanPriceController::class, 'update'])
                ->middleware('permission:UPDATE_PLAN_PRICE');
            Route::delete('delete/{id}', [PlanPriceController::class, 'delete'])
                ->middleware('permission:DELETE_PLAN_PRICE');
        });

        // Plan routes
        Route::prefix('plan')->group(function () {
            Route::post('create', [PlanController::class, 'store'])
                ->middleware('permission:CREATE_PLAN');
            Route::patch('update/{id}', [PlanController::class, 'update'])
                ->middleware('permission:UPDATE_PLAN');
            Route::delete('delete/{id}', [PlanController::class, 'delete'])
                ->middleware('permission:DELETE_PLAN');
            Route::post('addFeature', [PlanController::class, 'addFeature'])
                ->middleware('permission:ADD_PLAN_FEATURE');
            Route::delete('deleteFeature/{id}', [PlanController::class, 'deleteFeature'])
                ->middleware('permission:DELETE_PLAN_FEATURE');
            Route::patch('updateFeature/{id}', [PlanController::class, 'updateFeature'])
                ->middleware('permission:UPDATE_PLAN_FEATURE');
        });

        // Statistics routes
        Route::prefix('superAdminStatistics')->group(function () {
            Route::get('homeStatistics', [SuperAdminStatisticsController::class, 'homeStatistics'])
                ->middleware('permission:VIEW_STATISTICS');
            Route::get('getSubscriptionDistributionByPlan/{year}', [SuperAdminStatisticsController::class, 'getSubscriptionDistributionByPlan'])
                ->middleware('permission:VIEW_STATISTICS');
            Route::get('subscriptionsPerPlans', [SuperAdminStatisticsController::class, 'subscriptionsPerPlans'])
                ->middleware('permission:VIEW_STATISTICS');
            Route::get('subscriptionRequestsPerPlans', [SuperAdminStatisticsController::class, 'subscriptionRequestsPerPlans'])
                ->middleware('permission:VIEW_STATISTICS');
            Route::get('topRequestedPlan', [SuperAdminStatisticsController::class, 'topRequestedPlan'])
                ->middleware('permission:VIEW_STATISTICS');
            Route::get('getTotalVisitors', [SuperAdminStatisticsController::class, 'getTotalVisitors'])
                ->middleware('permission:VIEW_STATISTICS');
            Route::get('getAvgDailyVisits', [SuperAdminStatisticsController::class, 'getAvgDailyVisits'])
                ->middleware('permission:VIEW_STATISTICS');
            Route::get('planStatistics/{plan_id}', [SuperAdminStatisticsController::class, 'planStatistics'])
                ->middleware('permission:VIEW_PLAN_STATISTICS');
            Route::get('distributionOfPlanPricesRequests/{plan_id}', [SuperAdminStatisticsController::class, 'distributionOfPlanPricesRequests'])
                ->middleware('permission:VIEW_PLAN_STATISTICS');
            Route::get('/generators/{generator}/statistics', [SuperAdminStatisticsController::class, 'getGeneratorStatistics'])
                ->middleware('permission:VIEW_GENERATOR_STATISTICS');
            Route::get('/generators/{id}/info', [SuperAdminStatisticsController::class, 'getGenInfo'])
                ->middleware('permission:VIEW_GENERATOR_STATISTICS');
        });

        // Subscription request routes
        Route::prefix('subscriptionRequest')->group(function () {
            Route::get('getLastFive', [SubscriptionRequestController::class, 'getLastFive'])
                ->middleware('permission:VIEW_SUBSCRIPTION_REQUESTS');
            Route::get('getAll', [SubscriptionRequestController::class, 'getAll'])
                ->middleware('permission:VIEW_SUBSCRIPTION_REQUESTS');
            Route::post('approve/{id}', [SubscriptionRequestController::class, 'approve'])
                ->middleware('permission:APPROVE_SUBSCRIPTION_REQUEST');
            Route::post('reject/{id}', [SubscriptionRequestController::class, 'reject'])
                ->middleware('permission:REJECT_SUBSCRIPTION_REQUEST');
            Route::post('create', [SubscriptionRequestController::class, 'store'])
                ->middleware('permission:CREATE_SUBSCRIPTION_REQUEST');
        });

        // App info management routes
        Route::prefix('AppInfo')->group(function () {
            Route::post('createAboutApp', [AppInfoController::class, 'createAboutApp'])
                ->middleware('permission:MANAGE_ABOUT_APP');
            Route::patch('updateAboutApp', [AppInfoController::class, 'updateAboutApp'])
                ->middleware('permission:MANAGE_ABOUT_APP');
            Route::delete('deleteAboutApp', [AppInfoController::class, 'deleteAboutApp'])
                ->middleware('permission:MANAGE_ABOUT_APP');
            Route::post('createTermsAndConditions', [AppInfoController::class, 'createTermsAndConditions'])
                ->middleware('permission:MANAGE_TERMS_CONDITIONS');
            Route::patch('updateTermsAndConditions', [AppInfoController::class, 'updateTermsAndConditions'])
                ->middleware('permission:MANAGE_TERMS_CONDITIONS');
            Route::delete('deleteTermsAndConditions', [AppInfoController::class, 'deleteTermsAndConditions'])
                ->middleware('permission:MANAGE_TERMS_CONDITIONS');
            Route::post('createPrivacyPolicy', [AppInfoController::class, 'createPrivacyPolicy'])
                ->middleware('permission:MANAGE_PRIVACY_POLICY');
            Route::patch('updatePrivacyPolicy', [AppInfoController::class, 'updatePrivacyPolicy'])
                ->middleware('permission:MANAGE_PRIVACY_POLICY');
            Route::delete('deletePrivacyPolicy', [AppInfoController::class, 'deletePrivacyPolicy'])
                ->middleware('permission:MANAGE_PRIVACY_POLICY');
        });

        // Subscription routes
        Route::prefix('Subscription')->group(function () {
            Route::post('renew', [SubscriptionController::class, 'renew'])
                ->middleware('permission:RENEW_SUBSCRIPTION');
            Route::get('cancel', [SubscriptionController::class, 'cancel'])
                ->middleware('permission:CANCEL_SUBSCRIPTION');
        });

        // Complaint routes
        Route::prefix('complaint')->group(function () {
            Route::post('createCutComplaint', [ComplaintController::class, 'createCutComplaint'])
                ->middleware(['block', 'permission:CREATE_CUSTOMER_COMPLAINT']);
            Route::patch('updateCutComplaint/{complaint_id}', [ComplaintController::class, 'updateCutComplaint'])
                ->middleware('permission:UPDATE_COMPLAINT');
            Route::post('createComplaint', [ComplaintController::class, 'createComplaint'])
                ->middleware('permission:CREATE_COMPLAINT');
            Route::delete('deleteComplaint/{complaint_id}', [ComplaintController::class, 'deleteComplaint'])
                ->middleware('permission:DELETE_COMPLAINT');
            Route::get('getComplaints', [ComplaintController::class, 'getComplaints'])
                ->middleware('permission:VIEW_COMPLAINTS');
        });

        // Account routes
        Route::prefix('account')->group(function () {
            Route::get('getProfile', [AccountController::class, 'getProfile'])
                ->middleware('permission:VIEW_PROFILE');
            Route::patch('updateProfile', [AccountController::class, 'updateProfile'])
                ->middleware('permission:UPDATE_PROFILE');
            Route::post('blocking/{id}', [AccountController::class, 'blocking'])
                ->middleware('permission:BLOCK_ACCOUNTS');
            Route::get('getAll', [AccountController::class, 'getAll'])
                ->middleware('permission:VIEW_ALL_ACCOUNTS');
        });

        // Payment routes
        Route::get('payStripe/{request_id}', [PaymentController::class, 'createStripeCheckout'])
            ->middleware('permission:PROCESS_STRIPE_PAYMENT');
        Route::get('payCash/{request_id}', [PaymentController::class, 'handleCashPayment'])
            ->middleware('permission:PROCESS_CASH_PAYMENT');
        Route::get('stripe/success', [PaymentController::class, 'stripeSuccess'])->name('stripe.success');
        Route::get('stripe/cancel', [PaymentController::class, 'stripeCancel'])->name('stripe.cancel');

        Route::prefix('spendingPay')->middleware(['auth:api'])->group(function () {
            Route::post('paySpending/{counter_id}',[SpendingPaymentController::class,'createStripeCheckout'])
                ->middleware('permission:PROCESS_STRIPE_SPENDING_PAYMENT');
            Route::get('payCash/{counter_id}', [SpendingPaymentController::class, 'handleCashPayment'])
                ->middleware('permission:PROCESS_CACHE_SPENDING_PAYMENT');
            Route::get('stripe/success', [SpendingPaymentController::class, 'stripeSuccess'])->name('spendingStripe.success');
            Route::get('stripe/cancel', [SpendingPaymentController::class, 'stripeCancel'])->name('spendingStripe.cancel');


        });

        Route::prefix('spending')->group(function () {
            Route::post('create', [SpendingController::class,'create'])
                ->middleware('permission:CREATE_SPENDING');
            Route::patch('update/{id}', [SpendingController::class,'update'])
                ->middleware('permission:UPDATE_SPENDING');
            Route::delete('delete/{id}', [SpendingController::class,'delete'])
                ->middleware('permission:DELETE_SPENDING');
            Route::get('getAll/{counter_id}', [SpendingController::class,'getAll'])
                ->middleware('permission:GET_SPENDINGS');

        });
    });



//    Route::prefix('auth')->group(function () {
//        Route::post('register', [AuthController::class, 'register']);
//        Route::post('login', [AuthController::class, 'login']);
//        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
//    });
//    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify'])
//        ->name('verification.verify');
//    Route::prefix('email')->group(function () {
//
//        Route::post('/send-verification', [VerificationController::class, 'send'])
//            ->name('verification.send');
//
//        Route::post('/resend', [VerificationController::class, 'resend'])
//            ->middleware('throttle:3,1')
//            ->name('verification.resend');
//    });
//
//
//    Route::prefix('/password')->group(function () {
//        Route::post('/request', [PasswordController::class, 'request']);
//
//        Route::post('/resend', [PasswordController::class, 'resend'])->middleware('throttle:3,1');
//        Route::post('/reset', [PasswordController::class, 'reset']);
//        Route::get('/verify', [PasswordController::class, 'verify'])->name('verification.pass');
//
//    });
//
//    // routes/api.php
//        Route::prefix('generator')->middleware(['auth:api', 'role:admin'])->group(function () {
//        // Areas
//        Route::post('areas', [AreaController::class, 'store'])->middleware('permission:CREATE_AREAS');
//        Route::get('getAreas', [AreaController::class, 'index'])->middleware('permission:VIEW_AREAS');
//        Route::put('update/{id}',[AreaController::class,'update']);
//        // Box assignment to areas
//        Route::post('/areas/{area_id}/boxes', [AreaBoxController::class, 'assignBox'])->middleware('permission:ASSIGN_BOXES_TO_AREAS');
//        Route::delete('/areas/{area}/boxes/{box}', [AreaBoxController::class, 'removeBoxFromArea'])->middleware('permission:REMOVE_BOXES_FROM_AREAS');
//        Route::get('/areas/{area_id}/boxes', [AreaBoxController::class, 'getAreaBoxes'])->middleware('permission:VIEW_AREA_BOXES');
//
//        // Box management
//        Route::post('/boxes', [ElectricalBoxController::class, 'store'])->middleware('permission:CREATE_BOXES');
//        Route::get('/boxes/{id}', [ElectricalBoxController::class, 'get'])->middleware('permission:VIEW_BOXES');
//        Route::delete('/boxes', [ElectricalBoxController::class, 'destroy'])->middleware('permission:DELETE_BOXES');
//        Route::put('/box/update/{id}', [ElectricalBoxController::class, 'update'])->middleware('permission:UPDATE_BOXES');
//
//        // Counter management
//        Route::post('/counters', [CounterBoxController::class, 'create'])->middleware('permission:CREATE_COUNTERS');
//        Route::put('/counter/update/{id}', [CounterBoxController::class, 'update'])->middleware('permission:UPDATE_COUNTERS');
//        Route::delete('counters/{id?}', [CounterBoxController::class, 'destroy'])->middleware('permission:DELETE_COUNTERS');
//        Route::get('/counters', [CounterController::class, 'get'])->middleware('permission:view counters');
//
//        // Counter-box assignment
//        Route::get('/boxes/{box_id}/counters', [CounterBoxController::class, 'getBoxCounters'])->middleware('permission:VIEW_BOX_COUNTERS');
//        Route::get('/counters/{counter_id}/current-box', [CounterBoxController::class, 'getCurrentCounter'])->middleware('permission:VIEW_COUNTER_CURRENT_BOX');
//        Route::delete('/counters/remove-box', [CounterBoxController::class, 'removeCounter'])->middleware('permission:REMOVE_COUNTER_FROM_BOX');
//
//        // Employee management
//        Route::post('/createEmp', [EmployeeAuthController::class, 'create'])->middleware('permission:CREATE_EMPLOYEES');
//        Route::put('/updateEmp/{id}', [EmployeeAuthController::class, 'update'])->middleware('permission:UPDATE_EMPLOYEES');
//        Route::delete('deleteEmp/{id?}', [EmployeeAuthController::class, 'delete'])->middleware('permission:DELETE_EMPLOYEES');
//        Route::get('/getEmps/{generator_id}', [EmployeeAuthController::class, 'getEmployees'])->middleware('permission:VIEW_EMPLOYEES');
//        Route::get('/getEmp/{id}', [EmployeeAuthController::class, 'getEmployee'])->middleware('permission:VIEW_EMPLOYEES_DETAILS');
//
//        Route::get('/permissions', [EmployeeAuthController::class, 'getPermission']);
//    });
//
//
//        Route::middleware('auth:api')->group(function () {
//            Route::prefix('faq')->group(function () {
//                Route::middleware('role:superAdmin')->group(function () {
//                    Route::put('/update/{id}', [FaqController::class, 'updateFaq']);
//                    Route::delete('delete/{id}', [FaqController::class, 'deleteFaq']);
//                    Route::post('/store', [FaqController::class, 'createFaq']);
//                });
//                Route::get('get/{category}', [FaqController::class, 'getFaqByRole']);
//            });
//
//
//            Route::post('request', [GeneratorRequestController::class, 'store'])->middleware('role:user');
//            Route::prefix('/gen')->middleware('role:superAdmin')->group(function () {
//                Route::post('approve/{id}', [GeneratorRequestController::class, 'approve']);
//                Route::post('reject/{id}', [GeneratorRequestController::class, 'reject']);
//                Route::get('get', [GeneratorRequestController::class, 'pendingRequests']);
//            });
//
//            Route::prefix('customer')->group(function () {
//                Route::post('request', [CustomerRequestController::class, 'store']);
//                Route::post('approve/{id}', [CustomerRequestController::class, 'approveRequest']);
//                Route::post('reject/{id}', [CustomerRequestController::class, 'rejectRequest']);
//
//            });
//
//           Route::prefix('neighborhood')->middleware('role:superAdmin')->group(function () {
//                Route::post('store', [NeighborhoodController::class, 'store']);
//                Route::get('all', [NeighborhoodController::class, 'index']);
//                Route::get('show/{id}', [NeighborhoodController::class, 'show']);
//             });
//
//
//
//           Route::middleware('role:superAdmin')->group(function () {
//                        Route::prefix('feature')->group(function () {
//                            Route::get('getAll', [FeatureController::class, 'index']);
//                            Route::get('findById/{id}', [FeatureController::class, 'findById']);
//                            Route::post('create', [FeatureController::class, 'store']);
//                            Route::patch('update/{id}', [FeatureController::class, 'update']);
//                            Route::delete('delete/{id}', [FeatureController::class, 'delete']);
//
//                        });
//
//                        Route::prefix('planPrice')->group(function () {
//                            Route::post('create/{plan_id}', [PlanPriceController::class, 'store']);
//                            Route::patch('update/{id}', [PlanPriceController::class, 'update']);
//                            Route::delete('delete/{id}', [PlanPriceController::class, 'delete']);
//
//                        });
//
//                        Route::prefix('plan')->group(function () {
//                            Route::post('create', [PlanController::class, 'store']);
//                            Route::patch('update/{id}', [PlanController::class, 'update']);
//                            Route::delete('delete/{id}', [PlanController::class, 'delete']);
//                            Route::post('addFeature', [PlanController::class, 'addFeature']);
//                            Route::delete('deleteFeature/{id}', [PlanController::class, 'deleteFeature']);
//                            Route::patch('updateFeature/{id}', [PlanController::class, 'updateFeature']);
//                        });
//
//                        Route::prefix('superAdminStatistics')->group(function () {
//                            Route::get('homeStatistics', [SuperAdminStatisticsController::class, 'homeStatistics']);
//                            Route::get('getSubscriptionDistributionByPlan/{year}', [SuperAdminStatisticsController::class, 'getSubscriptionDistributionByPlan']);
//                            Route::get('subscriptionsPerPlans', [SuperAdminStatisticsController::class, 'subscriptionsPerPlans']);
//                            Route::get('subscriptionRequestsPerPlans', [SuperAdminStatisticsController::class, 'subscriptionRequestsPerPlans']);
//                            Route::get('topRequestedPlan', [SuperAdminStatisticsController::class, 'topRequestedPlan']);
//
//                            Route::get('getTotalVisitors', [SuperAdminStatisticsController::class, 'getTotalVisitors']);
//                            Route::get('getAvgDailyVisits', [SuperAdminStatisticsController::class, 'getAvgDailyVisits']);
//                            Route::get('planStatistics/{plan_id}', [SuperAdminStatisticsController::class, 'planStatistics']);
//                            Route::get('distributionOfPlanPricesRequests/{plan_id}', [SuperAdminStatisticsController::class, 'distributionOfPlanPricesRequests']);
//                        });
//
//                        Route::prefix('subscriptionRequest')->group(function () {
//                            Route::get('getLastFive', [SubscriptionRequestController::class, 'getLastFive']);
//                            Route::get('getAll', [SubscriptionRequestController::class, 'getAll']);
//                            Route::post('approve/{id}', [SubscriptionRequestController::class, 'approve']);
//                            Route::post('reject/{id}', [SubscriptionRequestController::class, 'reject']);
//
//
//                        });
//
//                        Route::prefix('AppInfo')->group(function () {
//                            Route::post('createAboutApp', [AppInfoController::class, 'createAboutApp']);
//                            Route::patch('updateAboutApp', [AppInfoController::class, 'updateAboutApp']);
//                            Route::delete('deleteAboutApp', [AppInfoController::class, 'deleteAboutApp']);
//                            Route::post('createTermsAndConditions', [AppInfoController::class, 'createTermsAndConditions']);
//                            Route::patch('updateTermsAndConditions', [AppInfoController::class, 'updateTermsAndConditions']);
//                            Route::delete('deleteTermsAndConditions', [AppInfoController::class, 'deleteTermsAndConditions']);
//                            Route::post('createPrivacyPolicy', [AppInfoController::class, 'createPrivacyPolicy']);
//                            Route::patch('updatePrivacyPolicy', [AppInfoController::class, 'updatePrivacyPolicy']);
//                            Route::delete('deletePrivacyPolicy', [AppInfoController::class, 'deletePrivacyPolicy']);
//
//                        });
//
//
//                        Route::get('/generators/{generator}/statistics', [SuperAdminStatisticsController::class
//                            , 'getGeneratorStatistics']);
//                        Route::get('/generators/{id}/info', [SuperAdminStatisticsController::class, 'getGenInfo']);
//                        Route::delete('/generators/{id}/', [GeneratorRequestController::class, 'delete']);
//
//                    });
//
//
//
//
//           Route::prefix('customer')->group(function () {
//                        Route::post('request', [CustomerRequestController::class, 'store']);
//                        Route::post('approve/{id}', [CustomerRequestController::class, 'approveRequest']);
//                        Route::post('reject/{id}', [CustomerRequestController::class, 'rejectRequest']);
//                        Route::get('getPending', [CustomerRequestController::class, 'pendingRequests']);
//
//                    });
//
//
//                    });
//
//            Route::middleware('role:admin')->group(function () {
//                        Route::prefix('Subscription')->group(function () {
//                            Route::post('renew', [SubscriptionController::class, 'renew']);
//                            Route::get('cancel', [SubscriptionController::class, 'cancel']);
//                        });
//                    });
//            Route::prefix('subscriptionRequest')->group(function () {
//                        Route::post('create', [SubscriptionRequestController::class, 'store']);
//                    });
//
//
//          Route::prefix('planPrice')->group(function () {
//                        Route::get('getAll/{plan_id}', [PlanPriceController::class, 'index']);
//                        Route::get('findById/{id}', [PlanPriceController::class, 'findById']);
//                    });
//
//
//        Route::prefix('plan')->group(function () {
//                        Route::get('getAll', [PlanController::class, 'index']);
//                        Route::get('findById/{id}', [PlanController::class, 'findById']);
//                    });
//
//
//
//        Route::prefix('AppInfo')->group(function () {
//                        Route::get('getAboutApp', [AppInfoController::class, 'getAboutApp']);
//                        Route::get('getTermsAndConditions', [AppInfoController::class, 'getTermsAndConditions']);
//                        Route::get('getPrivacyPolicy', [AppInfoController::class, 'getPrivacyPolicy']);
//
//                    });
//
//        Route::prefix('complaint')->group(function () {
//                        Route::post('createCutComplaint', [ComplaintController::class, 'createCutComplaint'])->middleware('block');
//                        Route::patch('updateCutComplaint/{complaint_id}', [complaintcontroller::class, 'updateCutComplaint'])->middleware('role:employee');
//                        Route::post('createComplaint', [complaintcontroller::class, 'createComplaint']);
//                        Route::delete('deleteComplaint/{complaint_id}', [complaintcontroller::class, 'deleteComplaint']);
//                        Route::get('getComplaints', [complaintcontroller::class, 'getComplaints'])->middleware('role:admin,superAdmin, employee');
//                    });
//
//        Route::prefix('spending')->group(function () {
//            Route::post('createSpending', []);
//                    });
//
//
//        Route::prefix('account')->group(function () {
//                        Route::get('getProfile', [AccountController::class, 'getProfile']);
//                        Route::patch('updateProfile', [AccountController::class, 'updateProfile']);
//                        Route::post('blocking/{id}', [AccountController::class, 'blocking'])->middleware('role:superAdmin');
//                        Route::get('getAll', [AccountController::class, 'getAll'])->middleware('role:superAdmin');
//
//                    });
//                });
//
//
//Route::prefix('powerGenerator')->group(function () {
//    Route::get('getForPlan/{id}', [PowerGeneratorController::class, 'getForPlan']);
//    Route::get('getAll', [PowerGeneratorController::class, 'getAll']);
//    Route::get('getLastSubscription/{id}', [SubscriptionController::class, 'getLastSubscription']);
//
//});
//Route::get('visitLandingPage', [SuperAdminStatisticsController::class, 'visitLandingPage']);
//Route::get('payStripe/{request_id}', [paymentController::class, 'createStripeCheckout']);
//Route::get('payCash/{request_id}', [paymentController::class, 'handleCashPayment']);
//Route::get('stripe/success', [paymentController::class, 'stripeSuccess'])->name('stripe.success');
//Route::get('stripe/cancel', [paymentController::class, 'stripeCancel'])->name('stripe.cancel');




