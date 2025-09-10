<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\Employee;

interface EmployeeRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function findEmployee(int $id);
    public function delete(int $id);
    public function deleteMultiple(array $ids);
    public function getEmployees(int $id);
    public function getEmp(int $generator_id);
    public function getPermissions($id);
    public function updateRole(Employee $employee,string $role) : void;


}
