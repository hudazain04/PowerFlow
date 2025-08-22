<?php

namespace App\Http\Controllers\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
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
        return ApiResponses::success($generator,__('generatorRequest.generatorRequest'),ApiCode::OK);
    }
    public function pendingRequests()
    {
        $requests = $this->service->getPendingRequests();
        return ApiResponses::success($requests,__('generatorRequest.approve'),ApiCode::OK);

    }

    public function approve(int $id)
    {
        $generator = $this->service->approveRequest($id);

        return ApiResponses::success($generator,__('generatorRequest.approve'),ApiCode::OK);

    }

    public function reject(int $id)
    {
        $this->service->rejectRequest($id);
        return ApiResponses::success(null,'success',ApiCode::OK);
    }
    public function delete(int $generator_id){
      $generator=$this->service->delete($generator_id);
      return ApiResponses::success($generator,'success',ApiCode::OK);
    }



}
