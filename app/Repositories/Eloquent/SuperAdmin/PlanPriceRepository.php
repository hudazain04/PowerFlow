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
        $PlanPrices=PlanPriceModel::where('plan_id',$plan_id)->get();
        return $PlanPrices->map(function ($PlanPrice){
            return PlanPriceDTO::fromModel($PlanPrice);
        });
    }

    public function find(int $id): PlanPriceDTO
    {
        $PlanPrice=PlanPriceModel::find($id);
        if ($PlanPrice)
        {
            return PlanPriceDTO::fromModel($PlanPrice);
        }
        else
        {
            throw new ErrorException(__('planPrice.notFound'),ApiCode::NOT_FOUND);
        }
    }

    public function create(PlanPriceDTO $PlanPriceDTO): PlanPriceDTO
    {
        $PlanPrice=PlanPriceModel::create($PlanPriceDTO->toArray());
        return PlanPriceDTO::fromModel($PlanPrice);
    }

    public function update(int $id, PlanPriceDTO $PlanPriceDTO): PlanPriceDTO
    {
        $PlanPrice=PlanPriceModel::find($id);
        if ($PlanPrice)
        {
            $PlanPrice->update($PlanPriceDTO->toArray());
            $PlanPrice->save();
            return $PlanPriceDTO::fromModel($PlanPrice);
        }
        else
        {
            throw new ErrorException(__('planPrice.notFound'),ApiCode::NOT_FOUND);
        }
    }

    public function delete(int $id): bool
    {
        $PlanPrice=PlanPriceModel::find($id);
        if ($PlanPrice)
        {
            return $PlanPrice->delete();
        }
        else
        {
            throw new ErrorException(__('planPrice.notFound'),ApiCode::NOT_FOUND);
        }
    }
}
