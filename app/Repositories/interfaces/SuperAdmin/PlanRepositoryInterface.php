<?php

namespace App\Repositories\interfaces\SuperAdmin;

use App\Models\Plan;
use Illuminate\Support\Collection;
use App\DTOs\PlanDTO;
use App\Models\Plan as PlanModel;


interface PlanRepositoryInterface
{
    public function all() : Collection;

    public function getAllByColumns(array $columns) : Collection;

    public function find(int $id) : ?PlanModel;

    public function create(array $data) : PlanModel;

    public function update(PlanModel $plan , array $data) : PlanModel;

    public function delete(PlanModel $plan) : bool;

    public function getFeatures(PlanModel $plan): Collection;

    public function getPlanPrices(PlanModel $plan): Collection;
}
