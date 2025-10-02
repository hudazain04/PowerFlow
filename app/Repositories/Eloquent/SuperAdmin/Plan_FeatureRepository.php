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
        return $Plan_features;
    }

    public function find(int $id): ?Plan_FeatureModel
    {
        $Plan_feature=Plan_FeatureModel::find($id);
        return $Plan_feature;
    }

    public function create(array $data): Plan_FeatureModel
    {
        $Plan_feature=Plan_FeatureModel::create($data);
        return $Plan_feature;
    }

    public function update(Plan_FeatureModel $plan_Feature, array $data): Plan_FeatureModel
    {
        $plan_Feature->update($data);
        $plan_Feature->save();
        return $plan_Feature;

    }

    public function delete(Plan_FeatureModel $plan_Feature): bool
    {
        return $plan_Feature->delete();
    }

    public function findByPlanAndFeature(int $feature_id, int $plan_id): ?Plan_FeatureModel
    {
        $Plan_feature=Plan_FeatureModel::where([
            'plan_id'=>$plan_id,'feature_id'=>$feature_id,
        ])->first();
        return $Plan_feature;
    }
}
