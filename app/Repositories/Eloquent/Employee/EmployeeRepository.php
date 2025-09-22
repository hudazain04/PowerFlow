<?php

namespace App\Repositories\Eloquent\Employee;

use App\Models\Employee;
use App\Repositories\interfaces\Employee\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{

    public function getPermissions(int $id)
    {
        $emp=Employee::find($id);
        $emp->getAllPermissions();
        return $emp;
    }
}
