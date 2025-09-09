<?php

namespace App\Repositories\Eloquent\Employee;

use App\Models\Employee;
use App\Repositories\interfaces\Employee\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\Log;

class EmployeeRepository implements EmployeeRepositoryInterface
{

    public function getPermissions($id)
    {

        $employee = Employee::where('id', $id)->first();

        if (!$employee) {
            throw new \Exception("Employee not found");
        }
        $permissions = $employee->getAllPermissions();

        return $permissions;

    }
}
