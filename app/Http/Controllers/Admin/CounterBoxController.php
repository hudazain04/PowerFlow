<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\CounterBoxRequest;
use App\Http\Requests\CounterRequest;
use App\Http\Requests\counterUpdateRequest;
use App\Http\Resources\CounterResource;
use App\Repositories\Eloquent\Admin\CounterBoxRepository;
use App\Services\Admin\CounterBoxService;
use Illuminate\Http\Request;

class CounterBoxController extends Controller
{
    public function __construct(private CounterBoxService $service) {}

    public function assignCounter(CounterBoxRequest $request)
    {
        $this->service->assignCounter(...$request->validated());

        return ApiResponses::success(null, 'Counter assigned to box successfully', ApiCode::OK);
    }
    public function create(CounterRequest $request){
        $result = $this->service->createCounter($request->validated());
        return ApiResponses::success(CounterResource::make($result), __('counter.create'), ApiCode::OK);

    }
    public function update(counterUpdateRequest $request, $id)
    {
        $result = $this->service->updateCounter($id, $request->validated());
        return ApiResponses::success(CounterResource::make($result), __('counter.update'), ApiCode::OK);
    }
    public function destroy(Request $request, $id = null)
    {
        if ($id) {
            $this->service->deleteCounter($id);
            return ApiResponses::success(null, __('counter.delete'), ApiCode::OK);
        }

        if ($request->has('ids')) {
            $ids = $request->input('ids');
            $this->service->deleteMultipleCounters($ids);
            return ApiResponses::success(null, __('counter.delete'), ApiCode::OK);
        }

        return ApiResponses::error(__('counter.noCountersToDelete'), ApiCode::BAD_REQUEST);
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
