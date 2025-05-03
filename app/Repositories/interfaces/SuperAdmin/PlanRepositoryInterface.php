<?php

namespace App\Repositories\interfaces\SuperAdmin;

use Illuminate\Support\Collection;
use App\DTOs\PlanDTO;

interface PlanRepositoryInterface
{
    public function all() : Collection;

    public function find(int $id) : PlanDTO;

    public function create(PlanDTO $planDTO) : PlanDTO;

    public function update(int $id , PlanDTO $planDTO) : PlanDTO;

    public function delete(int $id) : bool;

    public function getFeatures(int $plan_id): Collection;

    public function getPlanPrices(int $plan_id): Collection;
}
