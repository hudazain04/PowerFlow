<?php

namespace App\Repositories\interfaces\SuperAdmin;

use App\DTOs\Plan_FeatureDTO;
use Illuminate\Support\Collection;

interface Plan_FeatureRepositoryInterface
{
    public function all() : Collection;

    public function find(int $id) : Plan_FeatureDTO;

    public function create(Plan_FeatureDTO $plan_FeatureDTO) : Plan_FeatureDTO;

    public function update(int $id , Plan_FeatureDTO $plan_FeatureDTO) : Plan_FeatureDTO;

    public function delete(int $id) : bool;

    public function findByPlanAndFeature(int $feature_id, int $plan_id) : Plan_FeatureDTO;
}
