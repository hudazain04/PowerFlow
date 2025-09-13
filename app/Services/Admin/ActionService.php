<?php

namespace App\Services\Admin;

use App\ApiHelper\ApiCode;
use App\DTOs\ActionDTO;
use App\Exceptions\ErrorException;
use App\Repositories\interfaces\Admin\ActionRepositoryInterface;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use App\Types\CounterStatus;
use function Symfony\Component\Translation\t;

class ActionService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected ActionRepositoryInterface $actionRepository,
        protected EmployeeAssignmentService $employeeAssignmentService,
        protected CounterRepositoryInterface $counterRepository,
    )
    {
        //
    }

    public function create(array $data)
    {
        $action=$this->actionRepository->create($data);
        return $action;
    }

    public function update($action_id , ActionDTO $actionDTO)
    {
        $action=$this->actionRepository->find($action_id);
        if (!$action)
        {
            throw new ErrorException(__('action.notFound'),ApiCode::NOT_FOUND);
        }
        $action=$this->actionRepository->update($action,$actionDTO->toArray());
        $counter=$this->counterRepository->find($action->counter_id);
        if (! $counter)
        {
            throw new ErrorException(__('counter.notFound'),ApiCode::NOT_FOUND);
        }
        if ($action->type===ActionTypes::Cut && $action->status===ComplaintStatusTypes::Resolved)
        {
            $counter=$this->counterRepository->update($counter->id,['status'=>CounterStatus::DisConnected]);
        }
        elseif ($action->type===ActionTypes::Connect  && $action->status===ComplaintStatusTypes::Resolved)
        {
            $counter=$this->counterRepository->update($counter->id,['status'=>CounterStatus::Connect]);
        }

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

    public function find($action_id)
    {
        $action=$this->actionRepository->find($action_id);
        if (!$action)
        {
            throw new ErrorException(__('action.notFound'),ApiCode::NOT_FOUND);
        }
        return $action;
    }

    public function getAll($generator_id)
    {
        $actions=$this->actionRepository->getAll($generator_id);
        return $actions;
    }
}
