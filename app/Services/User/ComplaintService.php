<?php

namespace App\Services\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\ComplaintDTO;
use App\Exceptions\ErrorException;
use App\Http\Resources\ComplaintResource;
use App\Repositories\interfaces\User\ComplaintRepositoryInterface;
use App\Types\ComplaintStatusTypes;
use App\Types\ComplaintTypes;
use Illuminate\Http\Request;

class ComplaintService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected ComplaintRepositoryInterface  $complaintRepository,

    )
    {
        //
    }

    public function createCutComplaint(ComplaintDTO $complaintDTO)
    {
        $complaintDTO->status=ComplaintStatusTypes::Pending;
        $complaint=$this->complaintRepository->create($complaintDTO->toArray());
//        dd($complaint->type);
        $complaintDTO=ComplaintDTO::fromModel($complaint);
        return $this->success(ComplaintResource::make($complaintDTO),__('complaint.createCut'),ApiCode::CREATED);
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
