<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\clientsResource;
use App\Http\Resources\CounterResource;
use App\Http\Resources\UserWithCountersResource;
use App\Models\Counter;
use App\Models\User;
use App\Services\Admin\CounterService;
use Illuminate\Http\Request;
use Stripe\ApiResponse;

class CounterController extends Controller
{
    use \App\ApiHelper\ApiResponse;
    public function __construct(private CounterService $service){

    }

    public function index(int $generator_id)
    {
        $counters=$this->service->getCounters($generator_id);
        return ApiResponses::success(CounterResource::collection($counters),'counter for generator',ApiCode::OK);

    }
    public function get(Request $request){
        $counters=$this->service->get($request);
        return ApiResponses::success(CounterResource::collection($counters),__('messages.success'),ApiCode::OK);
    }
    // UserController.php
    // UserController.php
    public function getUserCounters(User $user)
    {
        $generatorId = auth()->user()->powerGenerator->id;
        $result = $this->service->getUserCountersInGenerator($user->id, $generatorId);
        return ApiResponses::success(
            UserWithCountersResource::make($result),
            __('user.counters_retrieved'),
            ApiCode::OK
        );
    }

    public function getGeneratorClients(Request $request)
    {
        $generatorId = auth()->user()->powerGenerator->id;
        $search = $request->input('search', '');
        $searchField = $request->input('field', 'all');

        $result = $this->service->getGeneratorClients($generatorId, $search, $searchField);

        return ApiResponses::success($this->successWithPagination(clientsResource::collection($result)), __('user.clients_retrieved'), ApiCode::OK
        );
    }

}
