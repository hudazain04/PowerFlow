<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\CreatePlanRequest;
use App\Services\SuperAdmin\PlanService;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    //

    public function __construct(protected PlanService $planService)
    {
    }

    public function index()
    {
        return $this->planService->getAll();
    }

    public function store(CreatePlanRequest $request)
    {
        return $this->planService->create($request);
    }

    public function update(int $id , CreatePlanRequest $request)
    {
        return $this->planService->update($id,$request);
    }

    public function findById( int $id)
    {
        return $this->planService->find($id);
    }

    public function delete(int $id )
    {
        return $this->planService->delete($id);
    }

}
