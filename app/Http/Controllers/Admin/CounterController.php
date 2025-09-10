<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\CounterResource;
use App\Models\Counter;
use App\Services\Admin\CounterService;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function __construct(private CounterService $service){

    }

    public function index(int $generator_id)
    {
        $counters=$this->service->getCounters($generator_id);
        return ApiResponses::success($counters,'counter for generator',ApiCode::OK);

    }
    public function get(Request $request){
        $counters=$this->service->get($request);
        return ApiResponses::success(CounterResource::collection($counters),__('messages.success'),ApiCode::OK);
    }

}
