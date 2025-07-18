<?php

namespace App\Repositories\Eloquent\Admin;
use App\Models\Employee;
use App\Repositories\interfaces\Admin\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    private $model;
    public function __construct(Employee $model){
        $this->model=$model;
    }

    public function create(array $data)
    {
//        return Employee::create([$data,
//           'secret_key'=>$this->model->generateSecretKey()]);

    }

    public function update(int $id, array $data)
    {
//       $emp=$this->findEmployee($id);
//       return Employee::update($emp,$data);
    }

    public function findEmployee(int $id)
    {
        return Employee::findOrFail($id);
    }

    public function delete(int $id)
    {
        $emp=$this->findEmployee($id);
        return Employee::delete($emp);

    }
    public function getEmployees(int $id)
    {
//        $emp=Employee::where('generator_id',$id)->get();
//        return $emp;
    }
}
