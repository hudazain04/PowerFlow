<?php

namespace App\Services\Admin;

use App\ApiHelper\ApiCode;
use App\DTOs\ActionDTO;
use App\Exceptions\ErrorException;
use App\Repositories\interfaces\Admin\ActionRepositoryInterface;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Services\NotificationService;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use App\Types\CounterStatus;
use App\Types\NotificationTypes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        protected NotificationService $notificationService,
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
        $user=$counter->user;
        if ($action->type===ActionTypes::Cut && $action->status===ComplaintStatusTypes::Resolved)
        {
            $this->counterRepository->update($counter->id,['status'=>CounterStatus::DisConnected]);
            $this->notificationService->notifyCustomUser([
                'title'=>__('notification.cut'),
                'body'=> $action->parent->type===ActionTypes::OverConsume ?
                    __('notification.cutOverCounter') :
                    __('notification.cutPayCounter'),
                'type'=>NotificationTypes::CustomUser,
                'ids'=>[$user->id],

            ]);
        }
        elseif ($action->type===ActionTypes::Connect  && $action->status===ComplaintStatusTypes::Resolved)
        {
           $this->counterRepository->update($counter->id,['status'=>CounterStatus::Connect]);
            $this->notificationService->notifyCustomUser([
                'title'=>__('notification.connect'),
                'body'=> __('notification.connectCounter'),
                'type'=>NotificationTypes::CustomUser,
                'ids'=>[$user->id],
            ]);
        }
        elseif ($action->type===ActionTypes::SetUp  && $action->status===ComplaintStatusTypes::Resolved)
        {
            $this->counterRepository->update($counter->id,['status'=>CounterStatus::Connect]);
            $this->notificationService->notifyCustomUser([
                'title'=>__('notification.connect'),
                'body'=> __('notification.connectCounter'),
                'type'=>NotificationTypes::CustomUser,
                'ids'=>[$user->id],
            ]);
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
            $counter=$action->counter;
            $response = Http::post(env('ESP_URL') . '/relay/connect/'.$counter->physical_device_id);
            if ($response->successful()) {
               $action=$this->actionRepository->update($newAction,[
                  'status' =>ComplaintStatusTypes::Resolved,
               ]);
               $this->counterRepository->update($counter->id,[
                  'status'=>CounterStatus::Connect,
               ]);

            }
        }
       elseif ($action->type===ActionTypes::Cut)
       {
           $action=$this->actionRepository->update($action,[
               'status'=>ComplaintStatusTypes::Accepted,
           ]);
           $counter=$action->counter;
           $response = Http::post(env('ESP_URL') . '/relay/disconnect/'.$counter->physical_device_id);
           if ($response->successful()) {
               $action=$this->actionRepository->update($action,[
                   'status' =>ComplaintStatusTypes::Resolved,
               ]);
               $this->counterRepository->update($counter->id,[
                   'status'=>CounterStatus::DisConnected,
               ]);

           }
       }
        elseif ($action->type===ActionTypes::OverConsume)
        {
            $action=$this->actionRepository->update($action,[
               'status'=>ComplaintStatusTypes::Resolved,
            ]);
            $newAction=$this->actionRepository->create([
                'type'=> ActionTypes::Cut,
                'status'=>ComplaintStatusTypes::Pending,
                'parent_id'=>$action->id,
                'counter_id'=>$action->counter_id,
                'generator_id'=>$action->generator_id,
            ]);
            $counter=$action->counter;
            $response = Http::post(env('ESP_URL') . '/relay/disconnect/'.$counter->physical_device_id);
            if ($response->successful()) {
                $action=$this->actionRepository->update($newAction,[
                    'status' =>ComplaintStatusTypes::Resolved,
                ]);
                $this->counterRepository->update($counter->id,[
                    'status'=>CounterStatus::DisConnected,
                ]);

            }

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

    public function getAll($generator_id,Request $request)
    {
        $actions=$this->actionRepository->getAll($generator_id,['type'=>$request->query('type'),'status'=>$request->query('status')]);
        return $actions;
    }

    public function getUserActions($user)
    {
        $actions=$this->actionRepository->getUserActions($user);
        return $actions;
    }
}
