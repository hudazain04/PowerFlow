<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\DTOs\PlanPriceDTO;
use App\Exceptions\ErrorException;
use App\Models\PlanPrice as PlanPriceModel;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use Illuminate\Support\Collection;

class PlanPriceRepository implements PlanPriceRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function all(int $plan_id): Collection
    {
        $planPrices=PlanPriceModel::where('plan_id',$plan_id)->get();
        return $planPrices;
    }

    public function find(int $id): PlanPriceModel
    {
        $planPrice=PlanPriceModel::find($id);
        return $planPrice;
    }

    public function create(array $data): PlanPriceModel
    {
        $planPrice=PlanPriceModel::create($data);
        return $planPrice;
    }

    public function update(PlanPriceModel $planPrice, array $data): PlanPriceModel
    {
        $planPrice->update($data);
        $planPrice->save();
        return $planPrice;
    }

    public function delete(PlanPriceModel $planPrice): bool
    {
        return $planPrice->delete();
    }
}
