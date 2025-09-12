<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiResponse;
use App\DTOs\ActionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Action\CreateActionRequest;
use App\Http\Resources\ActionResource;
use App\Services\Admin\ActionService;
use App\Services\Admin\EmployeeService;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

class ActionController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected ActionService $actionService,
        protected EmployeeService $employeeService,
    )
    {
    }

    public function create(CreateActionRequest $request)
    {
        $actionDTO=ActionDTO::fromRequest($request);
        $action=$this->actionService->create($actionDTO->toArray());
        return $this->success(ActionResource::make($action),__('action.ceate',['type' => $action->type]));

    }

    public function  approve($action_id)
    {
        $action=$this->actionService->approve($action_id);
        $employee=$this->employeeService->getEmployee($action->employee_id);
        return $this->success(ActionResource::make($action),__('action.assign',['employee'=>$employee->user_name]));
    }

    public function reject($action_id)
    {
        $this->actionService->reject($action_id);
        return $this->success(null,__('action.reject'));
    }
}
