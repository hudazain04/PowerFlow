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


    public function index()
    {
        $generatorId = auth()->user()->id;
        $areas = $this->service->getGeneratorAreas($generatorId);
        return  ApiResponses::success($areas,'success',ApiCode::OK);
    }

    public function boxes($id)
    {
        $boxes = $this->service->getAreaBoxes($id);
        return response()->json($boxes);
    }
}
