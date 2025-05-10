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
        return $plans;

    }

    public function find(int $id): PlanModel
    {
        $plan=PlanModel::find($id);
        return $plan;
    }

    public function create(array $data): PlanModel
    {
        $plan=PlanModel::create($data);
        return $plan;
    }

    public function update(PlanModel $plan, array $data): PlanModel
    {
        $plan->update($data);
        $plan->save();
        return $plan;
    }

    public function delete(PlanModel $plan): bool
    {
        return $plan->delete();
    }

    public function getFeatures(PlanModel $plan): Collection
    {

            $features=$plan->features;
             return $features;

//        ->map(function ($feature){
//        $featureDTO= FeatureDTO::fromModel($feature);
//        $featureDTO->value=$feature->pivot->value;
//        return $featureDTO;
//    })

    }

    public function getPlanPrices(PlanModel $plan): Collection
    {
            $planPrices=$plan->prices;
            return $planPrices;

    }
}
