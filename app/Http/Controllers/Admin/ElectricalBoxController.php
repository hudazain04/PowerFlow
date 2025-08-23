<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\AssignCounterToBoxDTO;
use App\DTOs\ElectricalBoxDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteERequest;
use App\Http\Requests\ElectricalBoxRequest;
use App\Models\Area;
use App\Models\ElectricalBox;
use App\Services\Admin\ElectricalBoxService;
use Illuminate\Http\Request;

class ElectricalBoxController extends Controller
{
    public function __construct(private ElectricalBoxService $service) {}


    public function store(ElectricalBoxRequest $request)
    {

        $box = $this->service->createBox($request->validated());

        return ApiResponses::success($box,'success',ApiCode::OK);
    }
    public function getGeneratorAreas()
    {
        $generatorId = auth()->user()->powerGenerator->id;
        $areas = Area::where('generator_id', $generatorId)->get();

        return ApiResponses::success($areas, 'Generator areas retrieved', ApiCode::OK);
    }
    public function getBoxes(int $generator_id){
        $Boxes=$this->service->getBoxes($generator_id);
        return ApiResponses::success($Boxes,'total boxes',ApiCode::OK);
    }
    public function get(int $generator_id){
        $Boxes=$this->service->get($generator_id);
        return ApiResponses::success($Boxes,'total boxes',ApiCode::OK);
    }
    public function update(int $id,ElectricalBoxRequest $request){
        $box = $this->service->updateBox($id, $request->validated());
        return ApiResponses::success($box, 'Box updated successfully', ApiCode::OK);
    }
//    public function destroy($id)
//    {
//        $this->service->deleteBox($id);
//
//        return ApiResponses::success(null,'success',ApiCode::OK);
//    }
    public function destroy(DeleteERequest $request)
    {
        $ids = $request->input('ids', []);
        $id = $request->input('id');


        if ($id) {
            $ids = [$id];
        }

      $this->service->deleteBoxes($ids);

        return ApiResponses::success(null,'success',ApiCode::OK);
    }

    public function bulkDestroy(BulkDeleteRequest $request)
    {
        $deletedCount = $this->service->bulkDeleteBoxes($request->ids);

        return ApiResponses::success(null,'success',ApiCode::OK);
    }

}
