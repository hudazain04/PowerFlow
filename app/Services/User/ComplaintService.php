<?php

namespace App\Services\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\ComplaintDTO;
use App\Events\ComplaintAssignEvent;
use App\Exceptions\ErrorException;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\CounterResource;
use App\Repositories\interfaces\Admin\EmployeeRepositoryInterface;
use App\Repositories\interfaces\User\ComplaintRepositoryInterface;
use App\Services\Admin\EmployeeAssignmentService;
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
        protected EmployeeAssignmentService $employeeAssignmentService,

    )
    {
        //
    }

    public function createCutComplaint(ComplaintDTO $complaintDTO)
    {
        $complaintDTO->status=ComplaintStatusTypes::Pending;
        $complaint=$this->complaintRepository->create($complaintDTO->toArray());
        $complaint=$this->complaintRepository->getRelations($complaint,['counter.electricalBoxes.areas']);
        $complaint = $this->employeeAssignmentService->assignToComplaint($complaint);
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
        $complaints=$this->complaintRepository->getAll([ 'type' => $request->query('type'),'search' => $request->query('search'),'status' => $request->query('status')]);
        $complaintsDTOs=$complaints->map(function ($complaint){
           $complaintDTO=ComplaintDTO::fromModel($complaint);
           return $complaintDTO;
        });
        return $this->successWithPagination(ComplaintResource::collection($complaints),__('messages.success'));

    }

    public function getEmployeeComplaints(Request $request)
    {
        $employee=$request->user();
        $complaints=$this->complaintRepository->getEmployeeComplaints($employee,['status' => $request->query('status')]);
        return $this->successWithPagination(ComplaintResource::collection($complaints),__('messages.success'));

    }

    public function getUserComplaints(Request $request)
    {
        $user=$request->user();
        $complaints=$this->complaintRepository->getUserComplaints($user,['status' => $request->query('status')]);
        return $this->successWithPagination(ComplaintResource::collection($complaints),__('messages.success'));

    }

}
