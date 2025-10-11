<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\Admin\EmployeeService;
use App\Types\UserTypes;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $service)
    {
    }
    public function create(EmployeeRequest $request)
    {
        $emp = $this->service->create(array_merge($request->validated(), ['generator_id' => $request->user()->id]));
        return ApiResponses::success(EmployeeResource::make($emp), __('employee.create'), ApiCode::OK);
    }
    public function update(UpdateEmployeeRequest $request, int $id)
    {
        $employee = $this->service->update($id, $request->validated());
        return ApiResponses::success(EmployeeResource::make($employee), __('employee.update'), ApiCode::OK);
    }
    public function delete(Request $request, int $id = null)
    {
        if ($id) {
            $employee = $this->service->delete($id);
            return ApiResponses::success(null, __('employee.delete'), ApiCode::OK);
        }
        if ($request->has('ids')) {
            $ids = $request->input('ids');
            $this->service->deleteMultiple($ids);
            return ApiResponses::success(null, __('employee.bulk_delete'), ApiCode::OK);
        }

        return ApiResponses::error(__('employee.noEmployeeIds'), ApiCode::BAD_REQUEST);

    }
    public function getEmployees(int $id)
    {
        $emp = $this->service->getEmployees($id);
        return ApiResponses::success(EmployeeResource::collection($emp),  __('employee.employees_retrieved'), ApiCode::OK);
    }
    public function getEmployee(int $id)
    {
        $emp = $this->service->getEmployee($id);
        return ApiResponses::success(EmployeeResource::make($emp), __('employee.employee_retrieved'), ApiCode::OK);
    }
    public function getEmp(int $generator_id)
    {
        $emp = $this->service->getEmp($generator_id);
        return ApiResponses::success(EmployeeResource::make($emp),__('employee.employee_retrieved'), ApiCode::OK);
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
        $permissions = $user->getPermissionNames();
        return ApiResponses::success($permissions,  __('employee.permissions_assigned'), ApiCode::OK);
    }
    public function getPermission()
    {
        $roleAdmin=Role::findByName(UserTypes::ADMIN);
        $adminPermissions=$roleAdmin->permissions->groupBy('group')->map(fn ($permissions) => $permissions->pluck('name'));;
        $roleEmployee=Role::findByName(UserTypes::EMPLOYEE);
        $employeePermissions=$roleEmployee->permissions->pluck('name');
//        $permissions = Permission::where('guard_name', 'api')
//            ->get()
//            ->groupBy('group');
        $permissions=[
            'adminPermissions'=>$adminPermissions,
            'employeePermissions'=>$employeePermissions,
        ];

        return ApiResponses::success($permissions, __('employee.permissions_retrieved'), ApiCode::OK);
    }
}
