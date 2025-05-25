<?php

namespace App\Http\Controllers;

use App\DTOs\FeatureDTO;
use App\DTOs\Plan_FeatureDTO;
use App\DTOs\PlanDTO;
use App\DTOs\PlanPriceDTO;
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
        $planDTO=PlanDTO::fromRequest($request);
        $planDTO->planPrices=collect($request->planPrices)->map(function ($planPrice){
           return $planPriceDTO=PlanPriceDTO::fromArray($planPrice);
        });
        $planDTO->features=collect($request->features)->map(function ($feature){
           return $featureDTO=Plan_FeatureDTO::fromArray(['feature_id'=>$feature['id'],'value'=>$feature['value']]);
        });
        return $this->planService->create($planDTO);
    }

    public function update(int $id , CreatePlanRequest $request)
    {
        $planDTO=PlanDTO::fromRequest($request);
        $planDTO->planPrices=collect($request->planPrices)->map(function ($planPrice){
            return $planPriceDTO=PlanPriceDTO::fromArray($planPrice);
        });
        $planDTO->features=collect($request->features)->map(function ($feature){
            return $featureDTO=Plan_FeatureDTO::fromArray(['feature_id'=>$feature['id'],'value'=>$feature['value']]);
        });
        return $this->planService->update($id,$planDTO);
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
