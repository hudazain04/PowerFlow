<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\DTOs\Plan_FeatureDTO;
use App\Models\Plan_Feature as Plan_FeatureModel;
use App\Repositories\interfaces\SuperAdmin\Plan_FeatureRepositoryInterface;
use Illuminate\Support\Collection;

class Plan_FeatureRepository implements Plan_FeatureRepositoryInterface
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
        $Plan_features=Plan_FeatureModel::all();
        return $Plan_features->map(function ($Plan_feature){
            return Plan_FeatureDTO::fromModel($Plan_feature);
        });
    }

    public function find(int $id): Plan_FeatureDTO
    {
        $Plan_feature=Plan_FeatureModel::findOrFail($id);
        return Plan_FeatureDTO::fromModel($Plan_feature);
    }

    public function create(Plan_FeatureDTO $plan_FeatureDTO): Plan_FeatureDTO
    {
        $Plan_feature=Plan_FeatureModel::create($plan_FeatureDTO->toArray());
        return Plan_FeatureDTO::fromModel($Plan_feature);
    }

    public function update(int $id, Plan_FeatureDTO $plan_FeatureDTO): Plan_FeatureDTO
    {
        $Plan_feature=Plan_FeatureModel::findOrFail($id);
        $Plan_feature->update($plan_FeatureDTO->toArray());
        $Plan_feature->save();
        return Plan_FeatureDTO::fromModel($Plan_feature);

    }

    public function delete(int $id): bool
    {
        $Plan_feature=Plan_FeatureModel::findOrFail($id);
        return $Plan_feature->delete();
    }

    public function findByPlanAndFeature(int $feature_id, int $plan_id): Plan_FeatureDTO
    {
        $Plan_feature=Plan_FeatureModel::where([
            'plan_id'=>$plan_id,'feature_id'=>$feature_id,
        ])->first();
        return Plan_FeatureDTO::fromModel($Plan_feature);
    }
}
