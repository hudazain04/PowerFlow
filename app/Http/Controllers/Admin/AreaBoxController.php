<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\AreaBoxRequest;
use App\Http\Resources\BoxResource;
use App\Http\Resources\ElectricalBoxResource;
use App\Repositories\Eloquent\Admin\AreaBoxRepository;
use App\Services\Admin\AreaBoxService;

class AreaBoxController extends Controller
{
    public function __construct(private AreaBoxService $service) {}
    public function assignBox(int $area_id, AreaBoxRequest $request)
    {
        $this->service->assignBox(
            $area_id,
            $request->validated()['box_id']
        );

        return ApiResponses::success(null,__('box.assign'),ApiCode::OK);
    }
    public function removeBoxFromArea(int $area_id, int $box_id)
    {
        $this->service->removeBoxFromArea($area_id, $box_id);

        return  ApiResponses::success(null,__('box.unAssigned'),ApiCode::OK);
    }
    public function getAvailableBoxes(int $area_id)
    {
        $boxes = $this->service->getAvailableBoxes($area_id);
        return ApiResponses::success($boxes, __('messages.success'), ApiCode::OK);
    }

    public function getAreaBoxes(int $area_id)
    {
        $boxes = $this->service->getAreaBoxes($area_id);
        return ApiResponses::success(ElectricalBoxResource::collection($boxes), __('messages.success'), ApiCode::OK);
    }
}
