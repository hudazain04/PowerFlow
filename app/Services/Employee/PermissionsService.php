<?php

namespace App\Services\Employee;

use App\Repositories\interfaces\Employee\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\Log;

class PermissionsService
{
    public function __construct(
        private EmployeeRepositoryInterface $repository
    ) {}

    public function getPermissions(int $id){

        $emp = $this->repository->getPermissions($id);

        return $emp;
    }

}
