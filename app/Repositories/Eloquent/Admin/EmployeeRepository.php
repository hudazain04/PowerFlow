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
        $user= Employee::create([
            'user_name' => $data['user_name'],
            'phone_number' => $data['phone_number'],
            'generator_id' => auth()->user()->powerGenerator->id,
            'secret_key' =>$this->model->generateSecretKey(),
//            'permissions'=> $data['permissions'],
        ],

        );
        if (array_key_exists('permissions',$data)) {
            $user->syncPermissions(...$data['permissions']);
        }


        return $user;
    }

    public function update(int $id, array $data)
    {
       $emp=$this->findEmployee($id);
        if (array_key_exists('permissions',$data)) {
            $emp->syncPermissions(...$data['permissions']);
        }

       return $emp->update($data);
    }

    public function findEmployee(int $id)
    {
        return Employee::findOrFail($id);
    }

    public function delete(int $id)
    {
        $emp=$this->findEmployee($id);
        return $emp->delete();

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

    public function deleteMultiple(array $ids)
    {
     $employees=Employee::whereIn('id',$ids)->get();
     foreach ($employees as $employee){
         $employee->delete();
     }
     return true;

    }
}
