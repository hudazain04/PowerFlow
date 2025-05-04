<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\DTOs\FeatureDTO;
use App\DTOs\PlanDTO;
use App\DTOs\PlanPriceDTO;
use App\Exceptions\ErrorException;
use App\Models\Plan;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Plan as PlanModel;

class PlanRepository implements PlanRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function all(): Collection
    {
        $plans=PlanModel::all();
        return $plans->map(function ($plan){
            return PlanDTO::fromModel($plan);
        });

    }

    public function find(int $id): PlanDTO
    {
        $plan=PlanModel::find($id);
        return PlanDTO::fromModel($plan);
    }

    public function create(PlanDTO $planDTO): PlanDTO
    {
        $plan=PlanModel::create($planDTO->toArray());
        return PlanDTO::fromModel($plan);
    }

    public function update(PlanDTO $plan, PlanDTO $planDTO): PlanDTO
    {
        $plan=$plan->toModel(PlanModel::class);
        $plan->update($planDTO->toArray());
        $plan->save();
        return $planDTO::fromModel($plan);
    }

    public function delete(PlanDTO $planDTO): bool
    {
        $plan=$planDTO->toModel(PlanModel::class);
        return $plan->delete();
    }

    public function getFeatures(int $plan_id): Collection
    {
        $plan=PlanModel::find($plan_id);
        if ($plan)
        {
            $features=$plan->features;
             return $features->map(function ($feature){
                $featureDTO= FeatureDTO::fromModel($feature);
                $featureDTO->value=$feature->pivot->value;
                return $featureDTO;
            });
        }
        else
        {
            throw new ErrorException(__('plan.notFound'),ApiCode::NOT_FOUND);
        }
    }

    public function getPlanPrices(int $plan_id): Collection
    {
        $plan=PlanModel::find($plan_id);
        if ($plan)
        {
            $planPrices=$plan->prices;
            return $planPrices->map(function ($planPrice){
                $planPriceDTO= PlanPriceDTO::fromModel($planPrice);
                return $planPriceDTO;
            });
        }
        else
        {
            throw new ErrorException(__('plan.notFound'),ApiCode::NOT_FOUND);
        }
    }
}
