<?php

namespace App\Repositories\interfaces\Admin;

interface EmployeeRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function findEmployee(int $id);
    public function delete(int $id);
    public function getEmployees(int $id);
    public function getEmp(int $generator_id);


}
