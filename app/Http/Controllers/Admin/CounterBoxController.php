<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\CounterBoxRequest;
use App\Repositories\Eloquent\Admin\CounterBoxRepository;
use App\Services\Admin\CounterBoxService;

class CounterBoxController extends Controller
{
    public function __construct(private CounterBoxService $service) {}

    public function assignCounter(CounterBoxRequest $request)
    {
        $this->service->assignCounter(...$request->validated());

        return ApiResponses::success(null, 'Counter assigned to box successfully', ApiCode::OK);
    }

    public function getBoxCounters($boxId)
    {
        $counters = $this->service->getBoxCounters($boxId);
        return ApiResponses::success($counters, 'Box counters retrieved successfully', ApiCode::OK);
    }
    public function getCurrentCounter(int $counterId)
    {
        $box = $this->service->getCurrentBox($counterId);

        if (!$box) {
            return ApiResponses::success(
                null,
                'Counter is not currently assigned to any box',
                ApiCode::OK
            );
        }

        return ApiResponses::success($box, 'Current box retrieved successfully', ApiCode::OK);
    }
    public function removeCounter(CounterBoxRequest $request)
    {
        $this->service->removeCounter( ...$request->validated());


        return ApiResponses::success(null, 'Counter removed from box successfully', ApiCode::OK);
    }
}
