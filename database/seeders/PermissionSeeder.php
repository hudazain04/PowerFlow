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

        $guards = ['api','employee'];

        foreach ($guards as $guard) {
            // Area permissions
            Permission::firstOrCreate(['name' => 'CREATE_AREAS', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'VIEW_AREAS', 'guard_name' => $guard]);

            // Box assignment permissions
            Permission::firstOrCreate(['name' => 'ASSIGN_BOXES_TO_AREAS', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'REMOVE_BOXES_FROM_AREAS', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'VIEW_AREA_BOXES', 'guard_name' => $guard]);
            // Box management permissions
            Permission::firstOrCreate(['name' => 'CREATE_BOXES', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'VIEW_BOXES', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'UPDATE_BOXES', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'DELETE_BOXES', 'guard_name' => $guard]);

            // Counter management permissions
            Permission::firstOrCreate(['name' => 'CREATE_COUNTERS', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'UPDATE_COUNTERS', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'DELETE_COUNTERS', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'VIEW_COUNTERS', 'guard_name' => $guard]);

            // Counter-box assignment permissions
            Permission::firstOrCreate(['name' => 'VIEW_BOX_COUNTERS', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'VIEW_COUNTER_CURRENT_BOX', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'REMOVE_COUNTER_FROM_BOX', 'guard_name' => $guard]);

            // Employee management permissions
            Permission::firstOrCreate(['name' => 'CREATE_EMPLOYEES', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'UPDATE_EMPLOYEES', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'DELETE_EMPLOYEES', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'VIEW_EMPLOYEES', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'VIEW_EMPLOYEE_DETAIL', 'guard_name' => $guard]);
            // Create admin role for this specific guard
            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => $guard
            ]);

            // Assign only guard-specific permissions to this role
            $guardPermissions = Permission::where('guard_name', $guard)->get();
            $adminRole->givePermissionTo($guardPermissions);
        }


    }
}
