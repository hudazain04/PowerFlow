<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\ApiHelper\HelperMethods;
use App\DTOs\FeatureDTO;
use App\DTOs\Plan_FeatureDTO;
use App\DTOs\PlanDTO;
use App\DTOs\PlanPriceDTO;
use App\Exceptions\ErrorException;
use App\Http\Requests\Plan\UpdateFeatureRequest;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Repositories\interfaces\SuperAdmin\Plan_FeatureRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;
use App\Http\Requests\Plan\CreatePlanRequest;
use App\Http\Resources\PlanResource;
use Illuminate\Support\Facades\DB;

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
        $plansDTOs=$plans->map(function ($plan){
            $planDTO=PlanDTO::fromModel($plan);
            $planFeatures=$this->planRepository->getFeatures($plan);
            $featureDTOs=$planFeatures->map(function ($feature){
                $featureDTO=FeatureDTO::fromModel($feature);
                $featureDTO->value=$feature->pivot->value;
                return $featureDTO;
            });
            $planDTO->features=$featureDTOs;
            $planPrices=$this->planRepository->getPlanPrices($plan);
            $planPricesDTOs=$planPrices->map(function ($planPrice){
               return $planPriceDTO=PlanPriceDTO::fromModel($planPrice);
            });
            $planDTO->planPrices=$planPricesDTOs;
            return $planDTO;
        });

        return $this->success(PlanResource::collection($plansDTOs),__('messages.success'));

    }

    public function create(PlanDTO $planDTO)
    {
        $plan= $this->planRepository->create($planDTO->toArray());
        $PlanDTO=PlanDTO::fromModel($plan);
        $planPriceDTOs = $planDTO->planPrices
            ->map(function($planPriceDTO) use ($plan) {
                $planPriceDTO->plan_id=$plan->id;
                $monthlyPrice=$plan->monthlyPrice;
                $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
                $planPriceDTO->price=$price;
                $planPrice= $this->planPriceRepository->create($planPriceDTO->toArray());
                return $planPriceDTO=PlanPriceDTO::fromModel($planPrice);
            }
            );
        $plan_featureDTOs =$planDTO->features
        ->map(function($feature) use ($plan){
            $feature->plan_id=$plan->id;
            $planFeature=$this->plan_FeatureRepository->create($feature->toArray());
           return $planFeatureDTO=Plan_FeatureDTO::fromModel($planFeature);
        }
        );
        $PlanDTO->planPrices=$planPriceDTOs;
        $PlanDTO->features=$this->planRepository->getFeatures($plan)->map(function ($feature){
            $featureDTO=FeatureDTO::fromModel($feature);
            $featureDTO->value=$feature->pivot->value;
            return $featureDTO;
        });
        return $this->success(PlanResource::make($PlanDTO),__('plan.create'),ApiCode::CREATED);
    }

    public function update(int $id , PlanDTO $planDTO)
    {
        $planModel=$this->planRepository->find($id);
        if ($planModel)
        {
            $PlanDTO=PlanDTO::fromModel($planModel);
            $plan=$this->planRepository->update($planModel,$planDTO->toArray());
            $planPriceDTOs = $planDTO->planPrices
                ->map(function($planPriceDTO) use ($plan) {
                    $planPriceDTO->plan_id=$plan->id;
                    if ($planPriceDTO->id)
                    {
                        $monthlyPrice=$plan->monthlyPrice;
                        $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
                        $planPriceDTO->price=$price;
                        $planPrice= $this->planPriceRepository->update($planPriceDTO->toModel(PlanPrice::class),$planPriceDTO->toArray());
                        return $planPriceDTO=PlanPriceDTO::fromModel($planPrice);

                    }
                    else
                    {
                        $monthlyPrice=$plan->monthlyPrice;
                        $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
                        $planPriceDTO->price=$price;
                        $planPrice= $this->planPriceRepository->create($planPriceDTO->toArray());
                        return $planPriceDTO=PlanPriceDTO::fromModel($planPrice);
                    }

                }
                );

            $plan_featureDTOs =$planDTO->features
                ->map(function($feature) use ($plan){
                    $feature->plan_id=$plan->id;
                    $plan_feature=$this->plan_FeatureRepository->findByPlanAndFeature($feature->feature_id,$plan->id);
                    if ($plan_feature)
                    {
                         $plan_feature=$this->plan_FeatureRepository->update($plan_feature,$feature->toArray());
                         return $plan_featureDTO=Plan_FeatureDTO::fromModel($plan_feature);
                    }
                    else
                    {
                        $plan_feature= $this->plan_FeatureRepository->create($feature->toArray());
                        return $plan_featureDTO=Plan_FeatureDTO::fromModel($plan_feature);
                    }
                }
                );
            $features=$this->planRepository->getFeatures($plan);
            $PlanDTO->planPrices=$planPriceDTOs;
            $PlanDTO->features=$features->map(function ($feature){
                $featureDTO=FeatureDTO::fromModel($feature);
                $featureDTO->value=$feature->pivot->value;
                return $featureDTO;
            });
            return $this->success(PlanResource::make($PlanDTO),__('plan.update'));
        }
        else
        {
            throw new ErrorException(__('plan.notFound'),ApiCode::NOT_FOUND);
        }
    }

    public function find(int $id)
    {
        $planModel=$this->planRepository->find($id);

        if ($planModel)
        {
            $planDTO=PlanDTO::fromModel($planModel);
            $planFeatures=$this->planRepository->getFeatures($planModel)->map(function ($feature){
                $featureDTO=FeatureDTO::fromModel($feature);
                $featureDTO->value=$feature->pivot->value;
                return $featureDTO;
            });
            $planDTO->features=$planFeatures;
            $prices=$this->planRepository->getPlanPrices($planModel)->map(function ($planPrice){
                return $planPriceDTO=PlanPriceDTO::fromModel($planPrice);
            });
            $planDTO->planPrices=$prices;
            return $this->success(PlanResource::make($planDTO),__('messages.success'));
        }
       else
       {
           throw new ErrorException(__('plan.notFound',ApiCode::NOT_FOUND));
       }

    }

    public function delete(int $id)
    {
        $planModel=$this->planRepository->find($id);
        if ($planModel)
        {
            $this->planRepository->delete($planModel);
            return $this->success(__('plan.delete'));
        }
        else
        {
            throw new ErrorException(__('plan.notFound'));
        }

    }



    public function addFeature(Plan_FeatureDTO $plan_FeatureDTO)
    {
        $plan_Feature=$this->plan_FeatureRepository->create($plan_FeatureDTO->toArray());
        return $this->success(null,__('plan.addFeature'),ApiCode::CREATED);
    }

    public function deleteFeature(int $id)
    {
        $planFeature=$this->plan_FeatureRepository->find($id);
        if (! $planFeature) {
            throw new ErrorException(__('feature.notFound'));
        }
        $this->plan_FeatureRepository->delete($planFeature);
        return $this->success(null,__('feature.delete'));
    }

    public function updateFeature(int $id,Plan_FeatureDTO $plan_FeatureDTO)
    {
        $plan_Feature=$this->plan_FeatureRepository->find($id);
        if (!  $plan_Feature)
        {
            throw new ErrorException(__('feature.notFound'));
        }
        $plan_Feature=$this->plan_FeatureRepository->update($plan_Feature,$plan_FeatureDTO->toArray());
        return $this->success(null,__('feature.update'));
    }
    public function getPlanFeatureValues($generator_id)
    {
        return DB::table('plan__features')
            ->select('features.key', 'plan__features.value')
            ->join('plans', 'plan__features.plan_id', '=', 'plans.id')
            ->join('plan_prices', 'plan_prices.plan_id', '=', 'plans.id')
            ->join('subscriptions', 'subscriptions.planPrice_id', '=', 'plan_prices.id')
            ->join('features', 'plan__features.feature_id', '=', 'features.id')
            ->where('subscriptions.generator_id', $generator_id)
            ->get();
    }



}
