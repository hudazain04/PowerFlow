<?php

namespace App\Http\Controllers\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\ApiHelper\ApiResponses;
use App\DTOs\CounterDTO;
use App\DTOs\CustomerRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\ProcessCustomerRequest;
use App\Http\Resources\CustomerRequestResource;
use App\Services\User\CustomerRequestService;
use App\Types\GeneratorRequests;
use Illuminate\Http\Request;

class CustomerRequestController extends Controller
{
    public function __construct(private CustomerRequestService $service) {}

    public function store(CustomerRequest $request)
    {
        $dto = CustomerRequestDTO::fromRequest($request);
        $dto->status=GeneratorRequests::PENDING;
        $customerRequest = $this->service->createRequest($dto);
        return ApiResponses::success(CustomerRequestResource::make($customerRequest),__('customerRequest.create'),ApiCode::CREATED);
    }

    public function approveRequest(int $id , CustomerRequest\ApproveRequest $request)
    {
        $dto=CustomerRequestDTO::fromRequest($request);
        $dto->status=GeneratorRequests::PENDING;
        $counter = $this->service->approveRequest($id ,$dto);

        return  ApiResponses::success($counter,'success',ApiCode::OK);

    }

    public function rejectRequest(int $id ,CustomerRequest\RejectRequest  $request)
    {
        $dto=CustomerRequestDTO::fromRequest($request);
        $this->service->rejectRequest($id,$dto);
        return ApiResponses::success(null,'success',ApiCode::OK);
    }

    public function pendingRequests()
    {
        $generatorId = auth()->user()->powerGenerator->id;
        $requests = $this->service->getPendingRequests($generatorId);

        return ApiResponses::success(CustomerRequestResource::collection($requests),__('messages.success'));
    }
}
