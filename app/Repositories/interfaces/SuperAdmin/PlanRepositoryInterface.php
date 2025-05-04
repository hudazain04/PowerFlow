<?php

namespace App\Repositories\interfaces\SuperAdmin;

use Illuminate\Support\Collection;
use App\DTOs\PlanDTO;

interface PlanRepositoryInterface
{
    public function all() : Collection;

    public function find(int $id) : PlanDTO;

    public function create(PlanDTO $planDTO) : PlanDTO;

    public function update(PlanDTO $plan , PlanDTO $planDTO) : PlanDTO;

    public function delete(PlanDTO $planDTO) : bool;

    public function getFeatures(int $plan_id): Collection;

    public function getPlanPrices(int $plan_id): Collection;
}
