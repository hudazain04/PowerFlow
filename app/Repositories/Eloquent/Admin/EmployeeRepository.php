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
        return Employee::create([
            'user_name' => $data['user_name'],
            'phone_number' => $data['phone_number'],
            'generator_id' => auth()->user()->powerGenerator->id,
            'secret_key' =>$this->model->generateSecretKey()]);
    }

    public function update(int $id, array $data)
    {
       $emp=$this->findEmployee($id);

       return $emp->update($data);
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
        $emp=Employee::where('generator_id',$id)->get();
        return $emp;
    }

    public function getEmp(int $generator_id)
    {
        return Employee::where('generator_id',$generator_id)->count();
    }
}
