<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\AssignCounterToBoxDTO;
use App\DTOs\ElectricalBoxDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElectricalBoxRequest;
use App\Models\ElectricalBox;
use App\Services\Admin\ElectricalBoxService;
use Illuminate\Http\Request;

class ElectricalBoxController extends Controller
{
    public function __construct(private ElectricalBoxService $service) {}

    public function store(ElectricalBoxRequest $request)
    {
        $dto = new ElectricalBoxDTO(
//            auth()->user()->powerGenerator->id,
//            $request->number,
//            $request->capacity
        );

        $box = $this->service->createBox($dto);

        return ApiResponses::success($box,'s',ApiCode::OK);
    }

    public function assignCounter(AssignCounterToBoxRequest $request)
    {
        $dto = new AssignCounterToBoxDTO(
//            $request->counter_id,
//            $request->box_id
        );

        $this->service->assignCounterToBox($dto);

        return response()->json(['message' => 'Counter assigned to box successfully']);
    }
    public function counters($id)
    {
        $counters = $this->service->getBoxCounters($id);
        return ApiResponses::success($counters,'s',ApiCode::OK);
    }

    public function available()
    {
        $generatorId = auth()->user()->powerGenerator->id;
        $boxes = $this->service->getAvailableBoxes($generatorId);
        return ApiResponses::success($boxes,'s',ApiCode::OK);
    }
}
