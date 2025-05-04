<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\ApiHelper\HelperMethods;
use App\DTOs\Plan_FeatureDTO;
use App\DTOs\PlanDTO;
use App\DTOs\PlanPriceDTO;
use App\Exceptions\ErrorException;
use App\Repositories\interfaces\SuperAdmin\Plan_FeatureRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;
use App\Http\Requests\Plan\CreatePlanRequest;
use App\Http\Resources\PlanResource;

class PlanService
{
    use HelperMethods;
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected PlanRepositoryInterface $planRepository,
        protected PlanPriceRepositoryInterface $planPriceRepository,
        protected Plan_FeatureRepositoryInterface $plan_FeatureRepository,
    )
    {
        //
    }
    public function getAll()
    {
        $plans= $this->planRepository->all();
        $features=$plans->map(function ($plan){
           $planFeatures=$this->planRepository->getFeatures($plan->id);
           $plan->features=$planFeatures;
        });
        $planPrices=$plans->map(function ($plan){
           $prices=$this->planRepository->getPlanPrices($plan->id);
           $plan->planPrices=$prices;
        });

        return $this->success(PlanResource::collection($plans),__('messages.success'));

    }

    public function create(CreatePlanRequest $request)
    {
        $planDTO=PlanDTO::fromRequest($request);
        $plan= $this->planRepository->create($planDTO);

        $planPriceDTOs = collect($request->planPrices)
            ->map(function($planPrice) use ($plan) {
                $planPriceDTO=PlanPriceDTO::fromArray(array_merge($planPrice, ['plan_id' => $plan->id]));
                $monthlyPrice=$plan->monthlyPrice;
                $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
                $planPriceDTO->price=$price;
                return $this->planPriceRepository->create($planPriceDTO);
            }
            );

        $plan_featureDTOs = collect($request->features)
        ->map(function($feature) use ($plan){
            $plan_featureDTO=Plan_FeatureDTO::fromArray(['plan_id'=>$plan->id,'feature_id'=>$feature['id'],'value'=>$feature['value']]);
            return $this->plan_FeatureRepository->create($plan_featureDTO);
        }
        );

        $features=$this->planRepository->getFeatures($plan->id);
        $plan->planPrices=$planPriceDTOs;
        $plan->features=$features;
        return $this->success(PlanResource::make($plan),__('plan.create'),ApiCode::CREATED);
    }

    public function update(int $id , CreatePlanRequest $request)
    {
        $planDTO=PlanDTO::fromRequest($request);
        $plan=$this->planRepository->find($id);
        if ($plan)
        {
            $plan=$this->planRepository->update($plan,$planDTO);
            $planPriceDTOs = collect($request->planPrices)
                ->map(function($planPrice) use ($plan) {
                    $planPriceDTO=PlanPriceDTO::fromArray(array_merge($planPrice, ['plan_id' => $plan->id]));
                    if ($planPrice['id'])
                    {
                        $monthlyPrice=$plan->monthlyPrice;
                        $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
                        $planPriceDTO->price=$price;
                        return $this->planPriceRepository->update($planPrice['id'],$planPriceDTO);

                    }
                    else
                    {
                        $monthlyPrice=$plan->monthlyPrice;
                        $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
                        $planPriceDTO->price=$price;
                        return $this->planPriceRepository->create($planPriceDTO);
                    }

                }
                );
            $plan_featureDTOs = collect($request->features)
                ->map(function($feature) use ($plan){
                    $plan_featureDTO=Plan_FeatureDTO::fromArray(['plan_id'=>$plan->id,'feature_id'=>$feature['id'],'value'=>$feature['value']]);
                    $plan_feature=$this->plan_FeatureRepository->findByPlanAndFeature($feature['id'],$plan->id);
                    if ($plan_feature)
                    {
                        return $this->plan_FeatureRepository->update($plan_feature->id,$plan_featureDTO);
                    }
                    else
                    {
                        return $this->plan_FeatureRepository->create($plan_featureDTO);
                    }
                }
                );

            $features=$this->planRepository->getFeatures($plan->id);
            $plan->planPrices=$planPriceDTOs;
            $plan->features=$features;
            return $this->success(PlanResource::make($plan),__('plan.update'));
        }
        else
        {
            throw new ErrorException(__('plan.notFound'),ApiCode::NOT_FOUND);
        }
    }

    public function find(int $id)
    {
        $plan=$this->planRepository->find($id);
        if ($plan)
        {
            $planFeatures=$this->planRepository->getFeatures($plan->id);
            $plan->features=$planFeatures;
            $prices=$this->planRepository->getPlanPrices($plan->id);
            $plan->planPrices=$prices;
            return $this->success(PlanResource::make($plan),__('messages.success'));
        }
       else
       {
           throw new ErrorException(__('plan.notFound',ApiCode::NOT_FOUND));
       }

    }

    public function delete(int $id)
    {
        $plan=$this->planRepository->find($id);
        if ($plan)
        {
            $this->planRepository->delete($plan);
            return $this->success(__('plan.delete'));
        }
        else
        {
            throw new ErrorException(__('plan.notFound'));
        }

    }


}
