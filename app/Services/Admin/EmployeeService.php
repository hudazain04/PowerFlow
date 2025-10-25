<?php

namespace App\Services\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Models\Employee;
use App\Repositories\interfaces\Admin\EmployeeRepositoryInterface;
use App\Services\EncryptionService;
use App\Types\UserTypes;

class EmployeeService

{
    public function __construct(private EmployeeRepositoryInterface $repository , protected EncryptionService $encryptionService){}

    public function create(array $data){

        $emp=$this->repository->create($data);
        $data2= $this->encryptionService->encryptDataForClient($emp,$data['clientPublicKey']);
        $this->repository->updateRole($emp['user'],UserTypes::EMPLOYEE);
        return $data2;
    }
    public function update(int $id,array $data){
        $employee=$this->repository->update($id,array_merge($data,['generator_id'=>auth()->user()->powerGenerator->id]));
        return $employee;
    }
    public function delete(int $id){

        $employee=$this->repository->delete($id);
        return $employee;
    }
    public function deleteMultiple(array $ids){
        $empolyees=$this->repository->deleteMultiple($ids);
        return $empolyees;
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
