<?php

namespace App\Http\Controllers\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\NeighborhoodDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\NeighborhoodRequest;
use App\Services\SuperAdmin\NeighborhoodService;

class NeighborhoodController extends Controller
{

    public function __construct(private NeighborhoodService $service) {}

    public function store(NeighborhoodRequest $request)
    {
        $dto = new NeighborhoodDTO(...$request->validated());

        $neighborhood = $this->service->createNeighborhood($dto);

        return ApiResponses::success($neighborhood,'success',ApiCode::OK);
    }
    public function index()
    {
        $neighborhoods = $this->service->getAllNeighborhoods();
        return ApiResponses::success($neighborhoods,'success',ApiCode::OK);
    }

    public function show($id)
    {
        $neighborhood = $this->service->getNeighborhood($id);
        return ApiResponses::success($neighborhood,'success',ApiCode::OK);
    }


}
