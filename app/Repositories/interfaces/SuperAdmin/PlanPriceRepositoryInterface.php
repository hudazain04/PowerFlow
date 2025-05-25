<?php

namespace App\Repositories\interfaces\SuperAdmin;

use App\DTOs\PlanPriceDTO;
use Illuminate\Support\Collection;
use App\Models\PlanPrice as PlanPriceModel;

interface PlanPriceRepositoryInterface
{
    public function all(int $plan_id) : Collection;

    public function find(int $id) : PlanPriceModel;

    public function create(array $data) : PlanPriceModel;

    public function update(PlanPriceModel $planPrice , array $data) : PlanPriceModel;

    public function delete(PlanPriceModel $planPrice) : bool;
}
