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

        $box = $this->service->createBox($request->validated());

        return ApiResponses::success($box,'success',ApiCode::OK);
    }
    public function getBoxes(int $generator_id){
        $Boxes=$this->service->getBoxes($generator_id);
        return ApiResponses::success($Boxes,'total boxes',ApiCode::OK);
    }

}
