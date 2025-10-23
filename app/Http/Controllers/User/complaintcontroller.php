<?php

namespace App\Http\Controllers\User;

use App\DTOs\ComplaintDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\CreateComplaintRequest;
use App\Http\Requests\Complaint\CreateCutComplaintRequest;
use App\Http\Requests\Complaint\UpdateCutComplaintRequest;
use App\Services\User\ComplaintService;
use App\Types\ComplaintTypes;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

class complaintcontroller extends Controller
{
    public function __construct(
        protected ComplaintService $complaintService,
    )
    {
    }

    public function createCutComplaint(CreateCutComplaintRequest $request)
    {
        $complaintDTO=ComplaintDTO::fromRequest($request);
        $complaintDTO->type=ComplaintTypes::Cut;
        $complaintDTO->user_id=$request->user()->id;
        return $this->complaintService->createCutComplaint($complaintDTO);
    }

    public function updateCutComplaint(int $complaint_id , UpdateCutComplaintRequest $request)
    {
        $complaintDTO=ComplaintDTO::fromRequest($request);
        $complaintDTO->user_id=$request->user()->id;
        return $this->complaintService->updateCutComplaint($complaint_id,$complaintDTO);
    }

    public function createComplaint(CreateComplaintRequest $request)
    {
        $complaintDTO=ComplaintDTO::fromRequest($request);
        $complaintDTO->type=ComplaintTypes::App;
        $complaintDTO->user_id=$request->user()->id;
        return $this->complaintService->createComplaint($complaintDTO);
    }

    public function deleteComplaint(int $id)
    {
        return $this->complaintService->deleteComplaint($id);
    }

    public function getComplaints(Request $request)
    {
        return $this->complaintService->getComplaints($request);
    }


    public function getUserComplaints(Request $request)
    {
        return $this->complaintService->getUserComplaints($request);
    }


    public function getEmployeeComplaints(Request $request)
    {
        return $this->complaintService->getEmployeeComplaints($request);
    }

}
