<?php

namespace App\Repositories\interfaces\SuperAdmin;

use App\DTOs\Plan_FeatureDTO;
use Illuminate\Support\Collection;
use App\Models\Plan_Feature as Plan_FeatureModel;


interface Plan_FeatureRepositoryInterface
{
    public function all() : Collection;

    public function find(int $id) : Plan_FeatureModel;

    public function create(array $data) : Plan_FeatureModel;

    public function update(Plan_FeatureModel $plan_Feature , array $data) : Plan_FeatureModel;

    public function delete(Plan_FeatureModel $plan_Feature) : bool;

    public function findByPlanAndFeature(int $feature_id, int $plan_id) : Plan_FeatureModel;
}
