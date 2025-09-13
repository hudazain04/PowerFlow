<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiResponse;
use App\DTOs\PowerGeneratorDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\PowerGenerator\UpdateInfoRequest;
use App\Http\Resources\PowerGeneratorResource;
use App\Services\Admin\PowerGeneratorService;
use Illuminate\Http\Request;

class PowerGeneratorController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected PowerGeneratorService $powerGeneratorService,
    )
    {
    }

    public function getForPlan(int $plan_id , Request $request)
    {
        return $this->powerGeneratorService->getForPlan($plan_id, $request->query());
    }

    public function getAll(Request $request)
    {
        return $this->powerGeneratorService->getAll( $request->query());

    }

    public function updateInfo($generator_id,UpdateInfoRequest $request)
    {
        $generatorDTO=PowerGeneratorDTO::fromRequest($request);
        $generator=$this->powerGeneratorService->updateInfo($generator_id,$generatorDTO);
        return $this->success(PowerGeneratorResource::make($generator),__('powerGenerator.update'));
    }
}
