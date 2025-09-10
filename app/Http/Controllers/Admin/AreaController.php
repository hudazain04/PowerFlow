<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\AreaDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AreaDeleteRequest;
use App\Http\Requests\AreaRequest;
use App\Http\Resources\AreaResource;
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

        return ApiResponses::success(AreaResource::make($area),__('area.create'),ApiCode::OK);
    }
    public function update(AreaRequest $request,int $id){
        $area=$this->service->updateArea($request->validated(),$id);
        return ApiResponses::success($area,'updates successfully',ApiCode::OK);
    }


    public function index()
    {
        $generatorId = auth()->user()->powerGenerator->id;
        $areas = $this->service->getGeneratorAreas($generatorId);
        return  ApiResponses::success(AreaResource::collection($areas),__('messages.success'),ApiCode::OK);
    }

    public function delete(AreaDeleteRequest $request){
        $ids = $request->input('ids', []);
//        $id = $request->input('id');

//        if ($id) {
//            $ids = [$id];
//        }

        $this->service->deleteAreas($ids);

        return ApiResponses::success(null, __('area delete success'), ApiCode::OK);
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
