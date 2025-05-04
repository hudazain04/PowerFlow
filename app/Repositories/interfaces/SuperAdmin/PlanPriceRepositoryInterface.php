<?php

namespace App\Repositories\interfaces\SuperAdmin;

use App\DTOs\PlanPriceDTO;
use Illuminate\Support\Collection;

interface PlanPriceRepositoryInterface
{
    public function all(int $plan_id) : Collection;

    public function find(int $id) : PlanPriceDTO;

    public function create(PlanPriceDTO $planPriceDTO) : PlanPriceDTO;

    public function update(PlanPriceDTO $planPrice , PlanPriceDTO $planPriceDTO) : PlanPriceDTO;

    public function delete(PlanPriceDTO $planPriceDTO) : bool;
}
