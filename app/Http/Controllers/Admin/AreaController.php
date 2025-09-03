<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\AreaDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Models\PowerGenerator;
use App\Services\Admin\AreaService;
use App\Services\Admin\CounterService;
use App\Services\Admin\ElectricalBoxService;

class AreaController extends Controller
{
    public function __construct(private AreaService $service
    ,private ElectricalBoxService $boxService,
    private CounterService $counterService) {}

    public function store(AreaRequest $request)
    {
        $dto = new AreaDTO(...$request->validated());

        $area = $this->service->createArea($dto);

        return ApiResponses::success($area,'success',ApiCode::OK);
    }
    public function update(AreaRequest $request,int $id){
        $area=$this->service->updateArea($request->validated(),$id);
        return ApiResponses::success($area,'updates successfully',ApiCode::OK);
    }


    public function index()
    {
        $generatorId = auth()->user()->powerGenerator->id;
        $areas = $this->service->getGeneratorAreas($generatorId);
        return  ApiResponses::success($areas,'success',ApiCode::OK);
    }
    public function getAreas(int $generator_id){
        $areas=$this->service->getAreas($generator_id);
        return ApiResponses::success($areas,"areas for generator",ApiCode::OK);
    }


    public function boxes($id)
    {
        $boxes = $this->service->getAreaBoxes($id);
        return response()->json($boxes);
    }


}
