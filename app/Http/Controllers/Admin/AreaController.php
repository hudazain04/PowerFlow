<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\AreaDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Services\Admin\AreaService;

class AreaController extends Controller
{
    public function __construct(private AreaService $service) {}

    public function store(AreaRequest $request)
    {
        $dto = new AreaDTO(...$request->validated());

        $area = $this->service->createArea($dto);

        return ApiResponses::success($area,'success',ApiCode::OK);
    }

    public function assignBox(AssignBoxToAreaRequest $request)
    {
        $dto = new AssignBoxToAreaDTO(
            $request->box_id,
            $request->area_id
        );

        $this->service->assignBoxToArea($dto);

        return response()->json(['message' => 'Box assigned to area successfully']);
    }
    public function index()
    {
        $generatorId = auth()->user()->powerGenerator->id;
        $areas = $this->service->getGeneratorAreas($generatorId);
        return response()->json($areas);
    }

    public function boxes($id)
    {
        $boxes = $this->service->getAreaBoxes($id);
        return response()->json($boxes);
    }
}
