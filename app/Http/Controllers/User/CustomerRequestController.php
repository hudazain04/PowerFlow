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
use App\Services\User\CustomerRequestService;
use Illuminate\Http\Request;

class CustomerRequestController extends Controller
{
    public function __construct(private CustomerRequestService $service) {}

    public function store(CustomerRequest $request)
    {
        $dto = new CustomerRequestDTO(...$request->validated());

        $customerRequest = $this->service->createRequest($dto);

        return ApiResponses::success($customerRequest,'success',ApiCode::OK);
    }

    public function approveRequest(int $id)
    {

        $counter = $this->service->approveRequest($id);

        return  ApiResponses::success($counter,'success',ApiCode::OK);

    }

    public function rejectRequest(int $id)
    {


        $this->service->rejectRequest($id);


        return ApiResponses::success(null,'success',ApiCode::OK);
    }

    public function pendingRequests()
    {
        $generatorId = auth()->user()->powerGenerator->id;
        $requests = $this->service->getPendingRequests($generatorId);

        return response()->json($requests);
    }
}
