<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Services\Admin\EmployeeService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $service){ }
    public function create(EmployeeRequest $request){
        $emp=$this->service->create([$request->validated(),'generator_id'=>$request->user()->id]);
        return ApiResponses::success($emp,'success',ApiCode::OK);
    }
    public function update(EmployeeRequest $request,int $id)
    {
        $employee = $this->service->update($id, $request->validated());
        return ApiResponses::success($employee, 'Employee updated successfully', ApiCode::OK);
    }
    public function delete(Request $request,int $id= null){
        if($id){
            $employee=$this->service->delete($id);
            return ApiResponses::success(null,'success',ApiCode::OK);
        }
        if ($request->has('ids')){
            $ids=$request->input('ids');
            $this->service->deleteMultiple($ids);
            return ApiResponses::success(null,'success',ApiCode::OK);
        }

        return ApiResponses::error('No employee IDs provided for deletion', ApiCode::BAD_REQUEST);

    }
    public function getEmployees(int $id){
        $emp=$this->service->getEmployees($id);
        return ApiResponses::success($emp,'success',ApiCode::OK);
    }
    public function getEmployee(int $id){
        $emp=$this->service->getEmployee($id);
        return ApiResponses::success($emp,'success',ApiCode::OK);
    }
    public function getEmp(int $generator_id){
        $emp=$this->service->getEmp($generator_id);
        return ApiResponses::success($emp,"success",ApiCode::OK);
    }
    public function assignPermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        $employee = Employee::findOrFail($id);
        $user = $employee->user;

        $user->syncPermissions($request->permissions);
        $permissions= $user->getPermissionNames();
        return ApiResponses::success($permissions,'success',ApiCode::OK);
    }
    public function getPermission()
    {
        $permissions = Permission::where('guard_name', 'api')
            ->get();

        return ApiResponses::success($permissions, 'Permissions retrieved', ApiCode::OK);
    }
}
