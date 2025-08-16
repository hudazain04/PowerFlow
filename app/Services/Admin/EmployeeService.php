<?php

namespace App\Services\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Models\Employee;
use App\Repositories\interfaces\Admin\EmployeeRepositoryInterface;

class EmployeeService

{
    public function __construct(private EmployeeRepositoryInterface $repository){}

    public function create(array $data){
        $emp=$this->repository->create($data);
        return $emp;
    }
    public function update(int $id,array $data){
        $emp=$this->repository->findEmployee($id);
        $employee=$this->repository->update($emp,$data);
        return $employee;
    }
    public function delete(int $id){
        $emp=$this->repository->findEmployee($id);
        $employee=$this->repository->delete($emp);
        return $employee;
    }
    public function getEmployees(int $id){
        $emp=$this->repository->getEmployees($id);
        return $emp;
    }
    public function getEmployee(int $id){
        $emp=$this->repository->findEmployee($id);
        return $emp;
    }
    public function getEmp(int $generator_id){
         return $this->repository->getEmp($generator_id);
    }

}
