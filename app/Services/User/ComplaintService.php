<?php

namespace App\Services\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\ComplaintDTO;
use App\Exceptions\ErrorException;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\CounterResource;
use App\Repositories\interfaces\Admin\EmployeeRepositoryInterface;
use App\Repositories\interfaces\User\ComplaintRepositoryInterface;
use App\Services\FirebaseService;
use App\Types\ComplaintStatusTypes;
use App\Types\ComplaintTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ComplaintService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected ComplaintRepositoryInterface  $complaintRepository,
        protected EmployeeRepositoryInterface $employeeRepository,

    )
    {
        //
    }

    public function createCutComplaint(ComplaintDTO $complaintDTO)
    {
        $complaintDTO->status=ComplaintStatusTypes::Pending;
        $complaint=$this->complaintRepository->create($complaintDTO->toArray());
        $complaint=$this->complaintRepository->getRelations($complaint,['counter.electricalBoxes.areas']);
        $box = $complaint->counter->electricalBoxes->first();
        $area = $box?->areas->first();
        $area_id=$area?->id;
//        dd($area_id);
        $box=$complaint->counter->electricalBoxes->first();
//        dd($box);
//        $closest = Redis::geosearch(
//            "geo:employees:{$area_id}",
//            'FROMLONLAT', $box->longitude, $box->latitude,
//            'BYRADIUS', 10, 'km',  // search within 50 km
//            'ASC',
//            'COUNT', 1,
//            'WITHDIST'
//        );
        $redis = Redis::connection()->client();

        $closest = $redis->rawCommand(
            'GEORADIUS',
            "geo:employees:{$area_id}",
            $box->longitude, $box->latitude,
            10, 'km',
            'WITHDIST',
            'COUNT', 10,
            'ASC'
        );

//        if (empty($results)) {
//            $this->error("No employees found in area {$areaId}");
//            return;
//        }

        if (!empty($closest)) {
            [$employee_id, $distance] = $closest[0];
        }
        $complaint=$this->complaintRepository->update($complaint,[
            'employee_id'=>$employee_id,
            'status'=>ComplaintStatusTypes::Assigned,
        ]);
        $complaint=$this->complaintRepository->getRelations($complaint,['counter.electricalBoxes']);
//        event(new \App\Events\ComplaintAssignEvent(
//            $complaint,
//            $employee_id,
//        ));
        $employee = $this->employeeRepository->findEmployee($employee_id);
        if (! $employee)
        {
            throw  new ErrorException(__('employee.notFound'),ApiCode::NOT_FOUND);
        }

        if ($employee->fcmToken) {
            FirebaseService::sendNotification(
                $employee->fcmToken,
                "New Complaint Assigned To You",
                "Complaint #{$complaint->id}: {$complaint->description}",
                [
                    'id'=>$complaint->id,
                    'status' => $complaint->status,
                    'description' => $complaint->description,
                    'type'=>$complaint->type,
                    'counter' => CounterResource::make($complaint->counter),
                    'created_at' => $complaint->created_at->format('Y-m-d h:s a'),
                    'latitude' => $box->latitude,
                    'longitude' => $box->longitude,
                ]
            );
        }
        return $this->success(ComplaintResource::make($complaint), __('complaint.createCut'), ApiCode::CREATED);
    }

    public function updateCutComplaint(int $complaint_id , ComplaintDTO $complaintDTO)
    {
        $complaint=$this->complaintRepository->find($complaint_id);
        if (! $complaint)
        {
            throw new ErrorException(__('complaint.notFound'),ApiCode::NOT_FOUND);
        }
        $complaint=$this->complaintRepository->update($complaint,$complaintDTO->toArray());
        $complaintDTO=ComplaintDTO::fromModel($complaint);
        return $this->success(ComplaintResource::make($complaintDTO),__('complaint.updateCut'));
    }

    public function createComplaint(ComplaintDTO $complaintDTO)
    {
        $complaint=$this->complaintRepository->create($complaintDTO->toArray());
        $complaintDTO=ComplaintDTO::fromModel($complaint);
        return $this->success(ComplaintResource::make($complaintDTO),__('complaint.create'));
    }

    public function deleteComplaint(int $id)
    {
        $complaint=$this->complaintRepository->find($id);
        if (! $complaint)
        {
            throw new ErrorException(__('complaint.notFound'),ApiCode::NOT_FOUND);
        }
        $this->complaintRepository->delete($complaint);
        return $this->success(__('complaint.delete'));
    }

    public function getComplaints(Request $request)
    {
        $complaints=$this->complaintRepository->getAll([ 'type' => $request->query('type')]);
        $complaintsDTOs=$complaints->map(function ($complaint){
           $complaintDTO=ComplaintDTO::fromModel($complaint);
           return $complaintDTO;
        });
        return $this->success(ComplaintResource::collection($complaintsDTOs),__('messages.success'));

    }


}
