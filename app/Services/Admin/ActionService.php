<?php

namespace App\Services\Admin;

use App\ApiHelper\ApiCode;
use App\Exceptions\ErrorException;
use App\Repositories\interfaces\Admin\ActionRepositoryInterface;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;

class ActionService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected ActionRepositoryInterface $actionRepository,
        protected EmployeeAssignmentService $employeeAssignmentService,
    )
    {
        //
    }

    public function create(array $data)
    {
        $action=$this->actionRepository->create($data);
        return $action;
    }

    public function approve($action_id)
    {
        $action=$this->actionRepository->find($action_id);
        if (! $action)
        {
            throw  new ErrorException(__('action.notFound'),ApiCode::NOT_FOUND);
        }
        if ($action->type===ActionTypes::Payment)
        {
            $action=$this->actionRepository->update($action,[
               'status'=>ComplaintStatusTypes::Resolved,
            ]);
            $newAction=$this->actionRepository->create([
                'type'=> ActionTypes::Connect,
                'status'=>ComplaintStatusTypes::Pending,
                'parent_id'=>$action->id,
                'counter_id'=>$action->counter_id,
                'generator_id'=>$action->generator_id,
            ]);
            $action = $this->employeeAssignmentService->assignToAction($newAction);
        }
       elseif ($action->type===ActionTypes::Cut)
       {
           $action=$this->actionRepository->update($action,[
               'status'=>ComplaintStatusTypes::Accepted,
           ]);
           $action=$this->employeeAssignmentService->assignToAction($action);
       }
        return  $action;
    }

    public function reject($action_id)
    {
        $action=$this->actionRepository->find($action_id);
        if (! $action)
        {
            throw  new ErrorException(__('action.notFound'),ApiCode::NOT_FOUND);
        }
        $this->actionRepository->update($action,[
           'status'=>ComplaintStatusTypes::Rejected,
        ]);
        return;
    }
}
