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

Route::middleware('lang')->group(function () {


    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    });
    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::prefix('email')->group(function () {
        Route::post('/send-verification', [VerificationController::class, 'send'])->name('verification.send');
        Route::post('/resend', [VerificationController::class, 'resend'])
            ->middleware('throttle:3,1')
            ->name('verification.resend');
    });
    Route::prefix('/password')->group(function () {
        Route::post('/request', [PasswordController::class, 'request']);
        Route::post('/resend', [PasswordController::class, 'resend'])->middleware('throttle:3,1');
        Route::post('/reset', [PasswordController::class, 'reset']);
        Route::prefix('generator')->middleware('role:admin')->group(function () {
            Route::post('/resend', [PasswordController::class, 'resend'])->middleware('throttle:3,1');
            Route::post('/reset', [PasswordController::class, 'reset']);
        });
        Route::get('/verify', [PasswordController::class, 'verify'])->name('verification.pass');
    });


    Route::prefix('generator')->middleware(['auth:api'])->group(function () {
        Route::middleware(['role:admin'])->group(function () {
            // Areas
            Route::post('areas', [AreaController::class, 'store'])->middleware('permission:CREATE_AREAS');
            Route::get('getAreas', [AreaController::class, 'index'])->middleware('permission:VIEW_AREAS');
            Route::put('update/{id}', [AreaController::class, 'update']);
            // Box assignment to areas
            Route::post('/areas/{area_id}/boxes', [AreaBoxController::class, 'assignBox'])->middleware('permission:ASSIGN_BOXES_TO_AREAS');
            Route::delete('/areas/{area}/boxes/{box}', [AreaBoxController::class, 'removeBoxFromArea'])->middleware('permission:REMOVE_BOXES_FROM_AREAS');
            Route::get('/areas/{area_id}/boxes', [AreaBoxController::class, 'getAreaBoxes'])->middleware('permission:VIEW_AREA_BOXES');

            // Box management
            Route::post('/boxes', [ElectricalBoxController::class, 'store'])->middleware('permission:CREATE_BOXES');
            Route::get('/boxes/{id}', [ElectricalBoxController::class, 'get'])->middleware('permission:VIEW_BOXES');
            Route::delete('/boxes', [ElectricalBoxController::class, 'destroy'])->middleware('permission:DELETE_BOXES');
            Route::put('/box/update/{id}', [ElectricalBoxController::class, 'update'])->middleware('permission:UPDATE_BOXES');

            // Counter management
            Route::post('/counters', [CounterBoxController::class, 'create'])->middleware('permission:CREATE_COUNTERS');
            Route::put('/counter/update/{id}', [CounterBoxController::class, 'update'])->middleware('permission:UPDATE_COUNTERS');
            Route::delete('counters/{id?}', [CounterBoxController::class, 'destroy'])->middleware('permission:DELETE_COUNTERS');
            Route::get('/counters', [CounterController::class, 'get'])->middleware('permission:view counters');

            // Counter-box assignment
            Route::get('/boxes/{box_id}/counters', [CounterBoxController::class, 'getBoxCounters'])->middleware('permission:VIEW_BOX_COUNTERS');
            Route::get('/counters/{counter_id}/current-box', [CounterBoxController::class, 'getCurrentCounter'])->middleware('permission:VIEW_COUNTER_CURRENT_BOX');
            Route::delete('/counters/remove-box', [CounterBoxController::class, 'removeCounter'])->middleware('permission:REMOVE_COUNTER_FROM_BOX');

            // Employee management
            Route::post('/createEmp', [EmployeeController::class, 'create'])->middleware('permission:CREATE_EMPLOYEES');
            Route::put('/updateEmp/{id}', [EmployeeController::class, 'update'])->middleware('permission:UPDATE_EMPLOYEES');
            Route::delete('deleteEmp/{id?}', [EmployeeController::class, 'delete'])->middleware('permission:DELETE_EMPLOYEES');
            Route::get('/getEmps/{generator_id}', [EmployeeController::class, 'getEmployees'])->middleware('permission:VIEW_EMPLOYEES');
            Route::get('/getEmp/{id}', [EmployeeController::class, 'getEmployee'])->middleware('permission:VIEW_EMPLOYEES_DETAILS');

            Route::get('/permissions', [EmployeeController::class, 'getPermission']);

        });
    });


        Route::middleware('auth:api')->group(function () {
            Route::prefix('faq')->group(function () {
                Route::middleware('role:superAdmin')->group(function () {
                    Route::put('/update/{id}', [FaqController::class, 'updateFaq']);
                    Route::delete('delete/{id}', [FaqController::class, 'deleteFaq']);
                    Route::post('/store', [FaqController::class, 'createFaq']);
                });
                Route::get('get/{category}', [FaqController::class, 'getFaqByRole']);
            });


            Route::post('request', [GeneratorRequestController::class, 'store'])->middleware('role:user');
            Route::prefix('/gen')->middleware('role:superAdmin')->group(function () {
                Route::post('approve/{id}', [GeneratorRequestController::class, 'approve']);
                Route::post('reject/{id}', [GeneratorRequestController::class, 'reject']);
                Route::get('get', [GeneratorRequestController::class, 'pendingRequests']);
            });

            Route::prefix('customer')->group(function () {
                Route::post('request', [CustomerRequestController::class, 'store']);
                Route::post('approve/{id}', [CustomerRequestController::class, 'approveRequest']);
                Route::post('reject/{id}', [CustomerRequestController::class, 'rejectRequest']);

            });

                Route::middleware(['auth:api', 'userContext'])->group(function () {

                    Route::prefix('generator')->middleware('role:admin')->group(function () {
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
                        Route::get('/boxes/{id}', [ElectricalBoxController::class, 'get']);
                        Route::delete('/boxes', [ElectricalBoxController::class, 'destroy']);
                        Route::put('/box/update/{id}', [ElectricalBoxController::class, 'update']);


// counter with boxes assignment///////
                        Route::post('/counters', [CounterBoxController::class, 'create']);
                        Route::put('/counter/update/{id}', [CounterBoxController::class, 'update']);
                        Route::delete('counters/{id?}', [CounterBoxController::class, 'destroy']);

//        Route::post('/counters/assign-box', [CounterBoxController::class, 'assignCounter']);
                        Route::get('/boxes/{box_id}/counters', [CounterBoxController::class, 'getBoxCounters']);
                        Route::get('/counters/{counter_id}/current-box', [CounterBoxController::class, 'getCurrentCounter']);
                        Route::delete('/counters/remove-box', [CounterBoxController::class, 'removeCounter']);
                        // employee creation/////////
                        Route::post('/createEmp', [EmployeeController::class, 'create']);
                        Route::put('/updateEmp/{id}', [EmployeeController::class, 'update']);
                        Route::delete('/deleteEmp/{id}', [EmployeeController::class, 'delete']);
                        Route::get('/getEmps/{generator_id}', [EmployeeController::class, 'getEmployees']);
                        Route::get('/getEmp/{id}', [EmployeeController::class, 'getEmployee']);
                    });
                    Route::prefix('faq')->group(function () {
                        Route::middleware('role:superAdmin')->group(function () {
                            Route::put('/update/{id}', [FaqController::class, 'updateFaq']);
                            Route::delete('delete/{id}', [FaqController::class, 'deleteFaq']);
                            Route::post('/store', [FaqController::class, 'createFaq']);
                        });
                        Route::get('get/{category}', [FaqController::class, 'getFaqByRole']);
                    });


                    Route::post('request', [GeneratorRequestController::class, 'store'])->middleware('role:user');
                    Route::prefix('/gen')->middleware('role:superAdmin')->group(function () {
                        Route::post('approve/{id}', [GeneratorRequestController::class, 'approve']);
                        Route::post('reject/{id}', [GeneratorRequestController::class, 'reject']);
                        Route::get('get', [GeneratorRequestController::class, 'pendingRequests']);
                    });
                    Route::prefix('neighborhood')->middleware('role:superAdmin')->group(function () {
                        Route::post('store', [NeighborhoodController::class, 'store']);
                        Route::get('all', [NeighborhoodController::class, 'index']);
                        Route::get('show/{id}', [NeighborhoodController::class, 'show']);
                    });
//
//            Route::prefix('generator')->middleware('role:admin')->group(function () {
//                // Areas//////
//                Route::post('areas', [AreaController::class, 'store']);
//                Route::get('getareas', [AreaController::class, 'index']);
//
//
//                // Box assignment to areas////
//                Route::post('/areas/{area_id}/boxes', [AreaBoxController::class, 'assignBox']);
//                Route::delete('/areas/{area}/boxes/{box}', [AreaBoxController::class, 'removeBoxFromArea']);
//                Route::get('/areas/{area_id}/boxes/available', [AreaBoxController::class, 'getAvailableBoxes']);
//                Route::get('/areas/{area_id}/boxes', [AreaBoxController::class, 'getAreaBoxes']);
//
//                // Box management////////
//
//                Route::post('/boxes', [ElectricalBoxController::class, 'store']);
//
//                // counter with boxes assignment///////
//                Route::post('/counters/assign-box', [CounterBoxController::class, 'assignCounter']);
//                Route::get('/boxes/{box_id}/counters', [CounterBoxController::class, 'getBoxCounters']);
//                Route::get('/counters/{counter_id}/current-box', [CounterBoxController::class, 'getCurrentCounter']);
//                Route::delete('/counters/remove-box', [CounterBoxController::class, 'removeCounter']);
//                // employee creation/////////
//                Route::post('/createEmp', [EmployeeController::class, 'create']);
//                Route::patch('/updateEmp/{id}', [EmployeeController::class, 'update']);
//                Route::delete('/deleteEmp/{id}', [EmployeeController::class, 'delete']);
//                Route::get('/getEmps/{generator_id}', [EmployeeController::class, 'getEmployees']);
//                Route::get('/getEmp/{id}', [EmployeeController::class, 'getEmployee']);
//            });


                    Route::middleware('role:superAdmin')->group(function () {
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
                            Route::get('getAll', [SubscriptionRequestController::class, 'getAll']);
                            Route::post('approve/{id}', [SubscriptionRequestController::class, 'approve']);
                            Route::post('reject/{id}', [SubscriptionRequestController::class, 'reject']);


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
                        Route::prefix('admin')->group(function () {
                            Route::get('getareas/{id}', [AreaController::class, 'getAreas']);
                            Route::get('getboxes/{id}', [ElectricalBoxController::class, 'getBoxes']);
                            Route::get('getcounters/{id}', [CounterController::class, 'index']);
                        });

                        Route::get('/generators/{generator}/statistics', [SuperAdminStatisticsController::class
                            , 'getGeneratorStatistics']);
                        Route::get('/generators/{id}/info', [SuperAdminStatisticsController::class, 'getGenInfo']);
                        Route::delete('/generators/{id}/', [GeneratorRequestController::class, 'delete']);

                    });

                    Route::middleware('role:admin')->group(function () {
                        Route::prefix('Subscription')->group(function () {
                            Route::post('renew', [SubscriptionController::class, 'renew']);
                            Route::get('cancel', [SubscriptionController::class, 'cancel']);
                        });
                    });

                    Route::prefix('subscriptionRequest')->group(function () {
                        Route::post('create', [SubscriptionRequestController::class, 'store']);
                    });


                    Route::prefix('planPrice')->group(function () {
                        Route::get('getAll/{plan_id}', [PlanPriceController::class, 'index']);
                        Route::get('findById/{id}', [PlanPriceController::class, 'findById']);
                    });

                    Route::prefix('customer')->group(function () {
                        Route::post('request', [CustomerRequestController::class, 'store']);
                        Route::post('approve/{id}', [CustomerRequestController::class, 'approveRequest']);
                        Route::post('reject/{id}', [CustomerRequestController::class, 'rejectRequest']);
                        Route::get('getPending', [CustomerRequestController::class, 'pendingRequests']);

                    });


                    });
                    Route::prefix('neighborhood')->middleware('role:superAdmin')->group(function () {
                        Route::post('store', [NeighborhoodController::class, 'store']);
                        Route::get('all', [NeighborhoodController::class, 'index']);
                        Route::get('show/{id}', [NeighborhoodController::class, 'show']);
                    });

                    Route::prefix('generator')->middleware('role:admin')->group(function () {
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


                    Route::middleware('role:superAdmin')->group(function () {
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
                            Route::get('getAll', [SubscriptionRequestController::class, 'getAll']);
                            Route::post('approve/{id}', [SubscriptionRequestController::class, 'approve']);
                            Route::post('reject/{id}', [SubscriptionRequestController::class, 'reject']);


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
                        Route::prefix('admin')->group(function () {
                            Route::get('getareas/{id}', [AreaController::class, 'getAreas']);
                            Route::get('getboxes/{id}', [ElectricalBoxController::class, 'getBoxes']);
                            Route::get('getcounters/{id}', [CounterController::class, 'index']);
                        });

                        Route::get('/generators/{generator}/statistics', [SuperAdminStatisticsController::class
                            , 'getGeneratorStatistics']);
                        Route::get('/generators/{id}/info', [SuperAdminStatisticsController::class, 'getGenInfo']);
                        Route::delete('/generators/{id}/', [GeneratorRequestController::class, 'delete']);

                    });

                    Route::middleware('role:admin')->group(function () {
                        Route::prefix('Subscription')->group(function () {
                            Route::post('renew', [SubscriptionController::class, 'renew']);
                            Route::get('cancel', [SubscriptionController::class, 'cancel']);
                        });
                    });

                    Route::prefix('subscriptionRequest')->group(function () {
                        Route::post('create', [SubscriptionRequestController::class, 'store']);
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

                    Route::prefix('spendingPay')->middleware(['auth:api'])->group(function () {
                        Route::post('paySpending/{counter_id}',[SpendingPaymentController::class,'createStripeCheckout']);
                        Route::get('payCash/{counter_id}', [SpendingPaymentController::class, 'handleCashPayment']);

                    });

                    Route::prefix('AppInfo')->group(function () {
                        Route::get('getAboutApp', [AppInfoController::class, 'getAboutApp']);
                        Route::get('getTermsAndConditions', [AppInfoController::class, 'getTermsAndConditions']);
                        Route::get('getPrivacyPolicy', [AppInfoController::class, 'getPrivacyPolicy']);

                    });

                    Route::prefix('complaint')->group(function () {
                        Route::post('createCutComplaint', [ComplaintController::class, 'createCutComplaint'])->middleware('block');
                        Route::patch('updateCutComplaint/{complaint_id}', [complaintcontroller::class, 'updateCutComplaint'])->middleware('role:employee');
                        Route::post('createComplaint', [complaintcontroller::class, 'createComplaint']);
                        Route::delete('deleteComplaint/{complaint_id}', [complaintcontroller::class, 'deleteComplaint']);
                        Route::get('getComplaints', [complaintcontroller::class, 'getComplaints'])->middleware('role:admin,superAdmin, employee');
                    });

                    Route::prefix('spending')->group(function () {
                        Route::post('create', [SpendingController::class,'create']);
                        Route::patch('update/{id}', [SpendingController::class,'update']);
                        Route::delete('delete/{id}', [SpendingController::class,'delete']);
                        Route::get('getAll/{counter_id}', [SpendingController::class,'getAll']);

                    });


                    Route::prefix('account')->group(function () {
                        Route::get('getProfile', [AccountController::class, 'getProfile']);
                        Route::patch('updateProfile', [AccountController::class, 'updateProfile']);
                        Route::post('blocking/{id}', [AccountController::class, 'blocking'])->middleware('role:superAdmin');
                        Route::get('getAll', [AccountController::class, 'getAll'])->middleware('role:superAdmin');

                    });
                });

                Route::prefix('powerGenerator')->group(function () {
                    Route::get('getForPlan/{id}', [PowerGeneratorController::class, 'getForPlan']);
                    Route::get('getAll', [PowerGeneratorController::class, 'getAll']);
                    Route::get('getLastSubscription/{id}', [SubscriptionController::class, 'getLastSubscription']);

                });

                Route::get('visitLandingPage', [SuperAdminStatisticsController::class, 'visitLandingPage']);


                Route::get('payStripe/{request_id}', [paymentController::class, 'createStripeCheckout']);
                Route::get('payCash/{request_id}', [paymentController::class, 'handleCashPayment']);
                Route::get('stripe/success', [paymentController::class, 'stripeSuccess'])->name('stripe.success');
                Route::get('stripe/cancel', [paymentController::class, 'stripeCancel'])->name('stripe.cancel');
                Route::get('stripe/success', [SpendingPaymentController::class, 'stripeSuccess'])->name('spendingStripe.success');
                Route::get('stripe/cancel', [SpendingPaymentController::class, 'stripeCancel'])->name('spendingStripe.cancel');

});



