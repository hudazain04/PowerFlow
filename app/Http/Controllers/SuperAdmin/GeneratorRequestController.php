<?php

namespace App\Http\Controllers\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\GeneratorDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterGeneratorRequest;
use App\Http\Resources\GeneratorRequestResource;
use App\Services\SuperAdmin\GeneratorRequestService;
use Illuminate\Support\Facades\DB;

class GeneratorRequestController extends Controller
{
  public function __construct(protected GeneratorRequestService $service){}

    public function store(RegisterGeneratorRequest $request)
    {
        $dto = new GeneratorDTO(...$request->validated());
        $generatorRequest = $this->service->createRequest($dto);
        $generator=GeneratorRequestResource::make($generatorRequest);
        return ApiResponse::success($generator,__('generatorRequest.generatorRequest'),ApiCode::OK);
    }
    public function pendingRequests()
    {
        $requests = $this->service->getPendingRequests();
        return ApiResponse::success($requests,__('generatorRequest.approve'),ApiCode::OK);

    }

    public function approve(int $id)
    {
        $generator = $this->service->approveRequest($id);

        return ApiResponse::success(null,__('generatorRequest.reject'),ApiCode::OK);

    }

    public function reject(int $id)
    {
        $this->service->rejectRequest($id);
        return ApiResponse::success(null,'sucess',ApiCode::OK);
    }

}
