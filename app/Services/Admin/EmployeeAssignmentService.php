<?php

namespace App\Services\Admin;

use App\Events\ComplaintAssignEvent;
use App\Events\ActionAssignEvent;
use App\Http\Resources\CounterResource;
use App\Models\Complaint;
use App\Models\Employee;
use App\Models\Action;
use App\Notifications\SystemNotification;
use App\Repositories\interfaces\Admin\EmployeeRepositoryInterface;
use App\Repositories\interfaces\User\ComplaintRepositoryInterface;
use App\Services\FirebaseService;
use App\Services\NotificationService;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use App\Types\NotificationTypes;
use ErrorException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

class EmployeeAssignmentService
{
    public function __construct(
        private ComplaintRepositoryInterface $complaintRepository,
        private EmployeeRepositoryInterface $employeeRepository,
        protected NotificationService $notificationService,
    ) {}

    /**
     * Find the closest employee in a given area and assign them to a complaint or action.
     */
    public function assignToComplaint(Complaint $complaint): Complaint
    {
        $box   = $complaint->counter->electricalBoxes->first();
        $area  = $box?->areas->first();
        $areaId = $area?->id;

        if (! $areaId) {
            throw new ErrorException(__('employee.noAreaFound'));
        }

        $redis = Redis::connection()->client();
        $closest = $redis->executeRaw(
            'GEORADIUS',
            "geo:employees:{$areaId}",
            $box->longitude, $box->latitude,
            10, 'km',
            'WITHDIST',
            'COUNT', 1,
            'ASC'
        );

        if (empty($closest)) {
            throw new ErrorException(__('employee.notFound'));
        }

        [$employeeId, $distance] = $closest[0];


        $complaint = $this->complaintRepository->update($complaint, [
            'employee_id' => $employeeId,
            'status'      => ComplaintStatusTypes::Assigned,
        ]);

        $employee = $this->employeeRepository->findEmployee($employeeId);
        if (! $employee) {
            throw new ErrorException(__('employee.notFound'));
        }

        event(new ComplaintAssignEvent($complaint, $employeeId));
        $this->notificationService->notifyCustomEmployee([
            'title'=>__('notification.complaint'),
            'body'=>__('notification.assignComplaint',[
                'id' => $complaint->id,
                'description' => $complaint->description,
            ]),
            'type'=>NotificationTypes::CustomEmployee,
            'ids'=>[$employee->id],
            'data'=>[
                'id'          => $complaint->id,
                'status'      => $complaint->status,
                'description' => $complaint->description,
                'type'        => $complaint->type,
                'counter'     => CounterResource::make($complaint->counter),
                'created_at'  => $complaint->created_at->format('Y-m-d h:s a'),
                'maps'=>[
                    'x'=>$box->latitude,
                    'y'=>$box->longitude,
                ],
            ],

        ]);
//        if ($employee->fcmToken) {
//            FirebaseService::sendNotification(
//                $employee->fcmToken,
//                "New Complaint Assigned To You",
//                "Complaint #{$complaint->id}: {$complaint->description}",
//                [
//                    'id'          => $complaint->id,
//                    'status'      => $complaint->status,
//                    'description' => $complaint->description,
//                    'type'        => $complaint->type,
//                    'counter'     => CounterResource::make($complaint->counter),
//                    'created_at'  => $complaint->created_at->format('Y-m-d h:s a'),
//                    'maps'=>[
//                        'x'=>$box->latitude,
//                        'y'=>$box->longitude,
//                    ],
//
//                ]
//            );
//        }

        return $complaint;
    }

    /**
     * Example for assigning an action (cut/connect).
     */
    public function assignToAction(Action $action): Action
    {
        $box   = $action->counter->electricalBoxes->first();
        $area  = $box?->areas->first();
        $areaId = $area?->id;

        $redis = Redis::connection()->client();
        $closest = $redis->executeRaw(
            'GEORADIUS',
            "geo:employees:{$areaId}",
            $box->longitude, $box->latitude,
            10, 'km',
            'WITHDIST',
            'COUNT', 1,
            'ASC'
        );

        if (!empty($closest)) {
            [$employeeId, $distance] = $closest[0];
            $action->update([
                'employee_id' => $employeeId,
                'status'=>ComplaintStatusTypes::Assigned
            ]);

            $employee = $this->employeeRepository->findEmployee($employeeId);
            if ($employee && $employee->fcmToken) {
                $data=[
                    'id'=>$action->id,
                    'type'=>$action->type,
                    'counter'=>CounterResource::make($action->counter),
                    'status'=>$action->status,
                    'employee_id'=>$action->employee_id,
                    'priority'=>$action->priority,
                    'parent_id'=>$action->parent_id,
                    'created_at'  => $action->created_at->format('Y-m-d h:s a'),
                    'maps'=>[
                        'x'=>$box->latitude,
                        'y'=>$box->longitude,
                    ],
                ];
                if ($action->type===ActionTypes::Payment)
                {
                    array_merge($data,['payment'=>$action->relatedData['payment']]);
                }
                elseif ($action->type===ActionTypes::Cut)
                {
                    array_merge($data,['latestSpending'=>$action->relatedData['latestSpending'],
                        'latestPayment'=>$action->relatedData['latestPayment']]);
                }

//                FirebaseService::sendNotification(
//                    $employee->fcmToken,
//                    "New Action Assigned To You",
//                    "Action #{$action->id}: {$action->type}",
//                   $data
//                );

                $notifyData=[
                    'title'=>__('notification.action'),
                    'body'=>__('notification.assignAction',[
                        'id' => $action->id,
                        'type' => $action->type,
                    ]),
                    'data'=>$data
                ];
                Notification::send($employee, new SystemNotification($data));
            }


            event(new ActionAssignEvent($action, $employeeId));
        }

        return $action;
    }
}
