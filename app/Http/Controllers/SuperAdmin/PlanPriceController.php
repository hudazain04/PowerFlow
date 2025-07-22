<?php

namespace App\Http\Controllers\SuperAdmin;

use App\DTOs\PlanPriceDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlanPrice\CreatePlanPriceRequest;
use App\Http\Requests\PlanPrice\UpdatePlanPriceRequest;
use App\Services\SuperAdmin\PlanPriceService;
use Illuminate\Http\Request;

class PlanPriceController extends Controller
{
    public function __construct(protected PlanPriceService $planPriceService)
    {

    }
    public function index(int $plan_id)
    {
        return $this->planPriceService->getAll($plan_id);
    }

    public function store(CreatePlanPriceRequest $request)
    {
        $planPriceDTO=PlanPriceDTO::fromRequest($request);
        return $this->planPriceService->create($planPriceDTO);
    }

    public function update(int $id , CreatePlanPriceRequest $request)
    {
        $planPriceDTO=PlanPriceDTO::fromRequest($request);
        return $this->planPriceService->update($id,$planPriceDTO);
    }

    public function findById( int $id)
    {
        return $this->planPriceService->find($id);
    }

    public function delete(int $id )
    {
        return $this->planPriceService->delete($id);
    }
}
