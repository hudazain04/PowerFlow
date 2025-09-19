<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $guards = ['api', 'employee'];
//
//        foreach ($guards as $guard) {
//            // Area permissions
//            Permission::firstOrCreate(['name' => 'CREATE_AREAS', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'VIEW_AREAS', 'guard_name' => $guard]);
//
//            // Box assignment permissions
//            Permission::firstOrCreate(['name' => 'ASSIGN_BOXES_TO_AREAS', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'REMOVE_BOXES_FROM_AREAS', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'VIEW_AREA_BOXES', 'guard_name' => $guard]);
//            // Box management permissions
//            Permission::firstOrCreate(['name' => 'CREATE_BOXES', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'VIEW_BOXES', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'UPDATE_BOXES', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'DELETE_BOXES', 'guard_name' => $guard]);
//
//            // Counter management permissions
//            Permission::firstOrCreate(['name' => 'CREATE_COUNTERS', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'UPDATE_COUNTERS', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'DELETE_COUNTERS', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'VIEW_COUNTERS', 'guard_name' => $guard]);
//
//            // Counter-box assignment permissions
//            Permission::firstOrCreate(['name' => 'VIEW_BOX_COUNTERS', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'VIEW_COUNTER_CURRENT_BOX', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'REMOVE_COUNTER_FROM_BOX', 'guard_name' => $guard]);
//
//            // Employee management permissions
//            Permission::firstOrCreate(['name' => 'CREATE_EMPLOYEES', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'UPDATE_EMPLOYEES', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'DELETE_EMPLOYEES', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'VIEW_EMPLOYEES', 'guard_name' => $guard]);
//            Permission::firstOrCreate(['name' => 'VIEW_EMPLOYEE_DETAIL', 'guard_name' => $guard]);
//            // Create admin role for this specific guard
//            $adminRole = Role::firstOrCreate([
//                'name' => 'admin',
//                'guard_name' => $guard
//            ]);
//
//            // Assign only guard-specific permissions to this role
//            $guardPermissions = Permission::where('guard_name', $guard)->get();
//            $adminRole->givePermissionTo($guardPermissions);
//        }
        $permissionGroups = [
            'Auth' => [
                'VERIFY_EMAIL',
                'RESEND_VERIFICATION',
                'REQUEST_PASSWORD_RESET',
                'RESET_PASSWORD',
            ],

            'Neighborhoods' => [
                'CREATE_NEIGHBORHOODS',
                'VIEW_NEIGHBORHOODS',
                'UPDATE_NEIGHBORHOODS',
                'DELETE_NEIGHBORHOODS',
                'VIEW_NEIGHBORHOOD',
            ],

            'Boxes' => [
                'CREATE_BOXES',
                'VIEW_BOXES',
                'UPDATE_BOXES',
                'DELETE_BOXES',
                'ASSIGN_BOXES_TO_NEIGHBORHOODS',
                'REMOVE_BOXES_FROM_NEIGHBORHOODS',
                'VIEW_NEIGHBORHOOD_BOXES',
            ],

            'Counters' => [
                'CREATE_COUNTERS',
                'VIEW_COUNTERS',
                'UPDATE_COUNTERS',
                'DELETE_COUNTERS',
                'VIEW_BOX_COUNTERS',
                'VIEW_COUNTER_CURRENT_BOX',
                'REMOVE_COUNTER_FROM_BOX',
            ],

            'Employees' => [
                'CREATE_EMPLOYEES',
                'VIEW_EMPLOYEES',
                'UPDATE_EMPLOYEES',
                'DELETE_EMPLOYEES',
                'VIEW_EMPLOYEE_DETAILS',
            ],

            'FAQ' => [
                'CREATE_FAQ',
                'VIEW_FAQ',
                'UPDATE_FAQ',
                'DELETE_FAQ',
            ],

            'Generator_Requests' => [
                'CREATE_GENERATOR_REQUEST',
                'VIEW_GENERATOR_REQUESTS',
                'APPROVE_GENERATOR_REQUEST',
                'REJECT_GENERATOR_REQUEST',
                'DELETE_GENERATOR',
                'VIEW_INFO',
                'UPDATE_GENERATOR_INFO'
            ],

            'Customer_Requests' => [
                'CREATE_CUSTOMER_REQUEST',
                'VIEW_CUSTOMER_REQUESTS',
                'APPROVE_CUSTOMER_REQUEST',
                'REJECT_CUSTOMER_REQUEST',
            ],

            'AREAS' => [
                'CREATE_AREA',
                'VIEW_AREAS',
                'UPDATE_AREA',
                'DELETE_AREA',
            ],

            'Features' => [
                'CREATE_FEATURE',
                'VIEW_FEATURES',
                'UPDATE_FEATURE',
                'DELETE_FEATURE',
            ],

            'Plans' => [
                'CREATE_PLAN',
                'VIEW_PLANS',
                'UPDATE_PLAN',
                'DELETE_PLAN',
                'ADD_PLAN_FEATURE',
                'DELETE_PLAN_FEATURE',
                'UPDATE_PLAN_FEATURE',
            ],

            'Plan_Prices' => [
                'CREATE_PLAN_PRICE',
                'VIEW_PLAN_PRICES',
                'UPDATE_PLAN_PRICE',
                'DELETE_PLAN_PRICE',
            ],

            'Subscriptions' => [
                'CREATE_SUBSCRIPTION',
                'VIEW_SUBSCRIPTIONS',
                'RENEW_SUBSCRIPTION',
                'CANCEL_SUBSCRIPTION',
            ],

            'Subscription_Requests' => [
                'CREATE_SUBSCRIPTION_REQUEST',
                'VIEW_SUBSCRIPTION_REQUESTS',
                'APPROVE_SUBSCRIPTION_REQUEST',
                'REJECT_SUBSCRIPTION_REQUEST',
            ],

            'App_Info' => [
                'MANAGE_ABOUT_APP',
                'MANAGE_TERMS_CONDITIONS',
                'MANAGE_PRIVACY_POLICY',
                'VIEW_ABOUT_APP',
                'VIEW_TERMS_CONDITIONS',
                'VIEW_PRIVACY_POLICY',
            ],

            'Statistics' => [
                'VIEW_STATISTICS',
                'VIEW_GENERATOR_STATISTICS',
                'VIEW_PLAN_STATISTICS',
            ],

            'Complaints' => [
                'CREATE_COMPLAINT',
                'CREATE_CUSTOMER_COMPLAINT',
                'UPDATE_COMPLAINT',
                'DELETE_COMPLAINT',
                'VIEW_COMPLAINTS',
            ],

            'Accounts' => [
                'VIEW_PROFILE',
                'UPDATE_PROFILE',
                'BLOCK_ACCOUNTS',
                'VIEW_ALL_ACCOUNTS',
            ],

            'Payments' => [
                'PROCESS_STRIPE_PAYMENT',
                'PROCESS_CASH_PAYMENT',
                'PROCESS_STRIPE_SPENDING_PAYMENT',
                'PROCESS_CACHE_SPENDING_PAYMENT'
            ],

            'Miscellaneous' => [
                'VIEW_LANDING_PAGE_STATS',
                'VIEW_POWER_GENERATORS',
            ],
            'Spendings'=>[
                'CREATE_SPENDING',
                'UPDATE_SPENDING',
                'DELETE_SPENDING',
              'GET_SPENDINGS',
            ],
            'Actions'=>[
                'CREATE_ACTION',
                'UPDATE_ACTION',
                'APPROVE_ACTION',
                'REJECT_ACTION',
                'VIEW_ACTION',
                'VIEW_ACTIONS'

            ],
            'Notifications'=>[
              'SEND_NOTIFICATION',
                'VIEW_NOTIFICATIONS',
                'VIEW_NOTIFICATION',
            ],
        ];
        foreach ($guards as $guard) {
            // Create all permissions
            foreach ($permissionGroups as $group => $permissions) {
                foreach ($permissions as $permission) {
                    Permission::firstOrCreate([
                        'name' => $permission,
                        'guard_name' => $guard,
                        'group' => $group,
                    ]);
                }
            }
            $superAdminRole = Role::firstOrCreate(['name' => 'superAdmin', 'guard_name' => $guard]);
            $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
            $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => $guard]);
            $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => $guard]);
            $superAdminRole->givePermissionTo(Permission::where('guard_name', $guard)->get());
            $adminPermissions = [
                'CREATE_NEIGHBORHOODS', 'VIEW_NEIGHBORHOODS','VIEW_NEIGHBORHOOD' ,'UPDATE_NEIGHBORHOODS', 'DELETE_NEIGHBORHOODS',
                'CREATE_BOXES', 'VIEW_BOXES', 'UPDATE_BOXES', 'DELETE_BOXES',
                'ASSIGN_BOXES_TO_NEIGHBORHOODS', 'REMOVE_BOXES_FROM_NEIGHBORHOODS', 'VIEW_NEIGHBORHOOD_BOXES',
                'CREATE_COUNTERS', 'VIEW_COUNTERS', 'UPDATE_COUNTERS', 'DELETE_COUNTERS',
                'VIEW_BOX_COUNTERS', 'VIEW_COUNTER_CURRENT_BOX', 'REMOVE_COUNTER_FROM_BOX',
                'CREATE_EMPLOYEES', 'VIEW_EMPLOYEES', 'UPDATE_EMPLOYEES', 'DELETE_EMPLOYEES',
                'VIEW_EMPLOYEE_DETAILS', 'VIEW_FAQ', 'CREATE_GENERATOR_REQUEST',
                'VIEW_CUSTOMER_REQUESTS', 'VIEW_AREAS', 'VIEW_FEATURES',
                'VIEW_PLANS', 'VIEW_PLAN_PRICES', 'CREATE_SUBSCRIPTION', 'VIEW_SUBSCRIPTIONS',
                'RENEW_SUBSCRIPTION', 'CANCEL_SUBSCRIPTION', 'CREATE_SUBSCRIPTION_REQUEST',
                'VIEW_ABOUT_APP', 'VIEW_TERMS_CONDITIONS', 'VIEW_PRIVACY_POLICY',
                'VIEW_STATISTICS', 'CREATE_COMPLAINT', 'CREATE_CUSTOMER_COMPLAINT',
                'VIEW_COMPLAINTS', 'VIEW_PROFILE', 'UPDATE_PROFILE', 'PROCESS_STRIPE_PAYMENT',
                'PROCESS_CASH_PAYMENT','PROCESS_STRIPE_SPENDING_PAYMENT', 'PROCESS_CACHE_SPENDING_PAYMENT',
                'VIEW_LANDING_PAGE_STATS', 'VIEW_POWER_GENERATORS','CREATE_SPENDING',
                'UPDATE_SPENDING','DELETE_SPENDING','GET_SPENDINGS','VIEW_INFO','UPDATE_GENERATOR_INFO',
                'CREATE_ACTION','UPDATE_ACTION','APPROVE_ACTION','REJECT_ACTION','VIEW_ACTION','VIEW_ACTIONS',
                'SEND_NOTIFICATION','VIEW_NOTIFICATIONS','VIEW_NOTIFICATION',
            ];

            $adminRole->givePermissionTo($adminPermissions);
            $employeePermissions = [
                'VIEW_AREAS', 'VIEW_BOXES', 'VIEW_COUNTERS', 'VIEW_BOX_COUNTERS',
                'VIEW_COUNTER_CURRENT_BOX', 'VIEW_EMPLOYEES', 'VIEW_FAQ',
                'CREATE_CUSTOMER_REQUEST', 'VIEW_CUSTOMER_REQUESTS', 'VIEW_NEIGHBORHOODS',
                'VIEW_PLANS', 'VIEW_PLAN_PRICES', 'VIEW_SUBSCRIPTIONS', 'CREATE_SUBSCRIPTION_REQUEST',
                'VIEW_ABOUT_APP', 'VIEW_TERMS_CONDITIONS', 'VIEW_PRIVACY_POLICY',
                'CREATE_COMPLAINT', 'CREATE_CUSTOMER_COMPLAINT', 'VIEW_COMPLAINTS',
                'VIEW_PROFILE', 'UPDATE_PROFILE', 'VIEW_POWER_GENERATORS','CREATE_SPENDING',
                'UPDATE_SPENDING','DELETE_SPENDING','GET_SPENDINGS','VIEW_NOTIFICATIONS','VIEW_NOTIFICATION'
            ];
            $employeeRole->givePermissionTo($employeePermissions);
            $userPermissions = [
                'CREATE_GENERATOR_REQUEST', 'CREATE_CUSTOMER_REQUEST',
                'CREATE_SUBSCRIPTION_REQUEST', 'VIEW_ABOUT_APP',
                'VIEW_TERMS_CONDITIONS', 'VIEW_PRIVACY_POLICY', 'CREATE_CUSTOMER_COMPLAINT',
                'VIEW_PROFILE', 'UPDATE_PROFILE', 'PROCESS_STRIPE_PAYMENT',
                'PROCESS_CASH_PAYMENT','PROCESS_STRIPE_SPENDING_PAYMENT' ,'PROCESS_CACHE_SPENDING_PAYMENT',
                'VIEW_PLANS', 'VIEW_PLAN_PRICES','GET_SPENDINGS','VIEW_NOTIFICATIONS','VIEW_NOTIFICATION',

            ];

            $userRole->givePermissionTo($userPermissions);

        }
    }
}
