<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\Admin\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
//    public function __construct(private EmployeeService $service){ }
//    public function create(array $data){
//        $emp=$this->service->create($data);
//        return ApiResponses::success($emp,'success',ApiCode::OK);
//    }
//    public function update(int $id,array $data){
//
//        $employee=$this->service->update($id,$data);
//        return ApiResponses::success($employee,'success',ApiCode::OK);
//    }
//    public function delete(int $id){
//
//        $employee=$this->service->delete($id);
//        return ApiResponses::success(null,'success',ApiCode::OK);
//    }
//    public function getEmployees(int $id){
//        $emp=$this->service->getEmployees($id);
//        return ApiResponses::success($emp,'success',ApiCode::OK);
//    }
//    public function getEmployee(int $id){
//        $emp=$this->service->getEmployee($id);
//        return ApiResponses::success($emp,'success',ApiCode::OK);
//    }
}
