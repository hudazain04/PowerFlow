<?php

namespace App\Http\Controllers\Employee;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Exceptions\AuthException;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeLoginRequest;
use App\Models\Employee;
use App\Services\Employee\PermissionsService;
use Clue\Redis\Protocol\Model\Request;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeAuthController extends Controller
{
    public function __construct(private PermissionsService $service){}

    public function login(EmployeeLoginRequest $request){

        $credentials = $request->only('phone_number', 'secret_key');

        $employee = Employee::where('phone_number', $credentials['phone_number'])->first();

        if (!$employee || $employee->secret_key !== $credentials['secret_key']) {
            return AuthException::invalidCredentials();
        }

        $token = Auth::guard('employee')->login($employee);
        $result=["user"=>$employee,"token"=>$token];

        return ApiResponses::success($result,__('employee.login_success'),ApiCode::OK);
    }
    public function logout()
    {
        Auth::guard('employee')->logout();

        return ApiResponses::success(null,__('employee.logout_success'),ApiCode::OK);
    }

    public function getPermissions(int $id){

            $permissions = $this->service->getPermissions($id);
            return ApiResponses::success($permissions, __('employee.permissions_retrieved'), ApiCode::OK);


    }


}
