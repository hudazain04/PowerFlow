<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\ApiHelper\HelperMethods;
use App\DTOs\PlanDTO;
use App\DTOs\PlanPriceDTO;
use App\Exceptions\ErrorException;
use App\Http\Requests\planPrice\CreatePlanPriceRequest;
use App\Http\Requests\PlanPrice\UpdatePlanPriceRequest;
use App\Http\Resources\PlanPriceResource;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;

class PlanPriceService
{
    use HelperMethods;
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected PlanPriceRepositoryInterface $planPricePriceRepository,
        protected PlanRepositoryInterface $planRepository
    )
    {
        //
    }
    public function getAll(int $plan_id)
    {
        $planPrices= $this->planPricePriceRepository->all($plan_id);
        $planPricesDTOs=$planPrices->map(function ($planPrice){
            return PlanPriceDTO::fromModel($planPrice);
        });
        return $this->success(PlanPriceResource::collection($planPricesDTOs),__('messages.success'));

    }

    public function create(PlanPriceDTO $planPriceDTO)
    {
//        dd($planPriceDTO);
        $plan=$this->planRepository->find($planPriceDTO->plan_id);
        $planDTO=PlanDTO::fromModel($plan);
        $monthlyPrice=$planDTO->monthlyPrice;
        $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
        $planPriceDTO->price=$price;
        $planPrice= $this->planPricePriceRepository->create($planPriceDTO->toArray());
        $planPriceDTO=PlanPriceDTO::fromModel($planPrice);
        return $this->success(PlanPriceResource::make($planPriceDTO),__('planPrice.create'),ApiCode::CREATED);
    }

    public function update(int $id , PlanPriceDTO $planPriceDTO)
    {
        $plan=$this->planRepository->find($planPriceDTO->plan_id);
        if ($plan)
        {
            $planDTO=PlanDTO::fromModel($plan);
            $PlanPrice=$this->planPricePriceRepository->find($id);
            if ($PlanPrice)
            {
                $planPriceDTO=PlanPriceDTO::fromModel($PlanPrice);
                $monthlyPrice=$planDTO->monthlyPrice;
                $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
                $planPriceDTO->price=$price;
                $planPrice=$this->planPricePriceRepository->update($PlanPrice,$planPriceDTO->toArray());
                $planPriceDTO=PlanPriceDTO::fromModel($planPrice);
                return $this->success(PlanPriceResource::make($planPriceDTO),__('planPrice.update'));
            }
            else
            {
                throw new ErrorException(__('planPrice.notFound'),ApiCode::NOT_FOUND);
            }


        }
        else
        {
            throw new ErrorException(__('plan.notFound'),ApiCode::NOT_FOUND);
        }

    }

    public function find(int $id)
    {
        $planPrice=$this->planPricePriceRepository->find($id);
        if ($planPrice)
        {
            $planPriceDTO=PlanPriceDTO::fromModel($planPrice);
            return $this->success(PlanPriceResource::make($planPriceDTO),__('messages.success'));
        }
        else
        {
            throw new ErrorException(__('planPrice.notFound'),ApiCode::NOT_FOUND);
        }

    }

    public function delete(int $id)
    {
        $planPrice=$this->planPricePriceRepository->find($id);
        if ($planPrice)
        {
            $this->planPricePriceRepository->delete($planPrice);
            return $this->success(__('planPrice.delete'));
        }
        else
        {
            throw new ErrorException(__('planPrice.notFound'),ApiCode::NOT_FOUND);
        }
    }

}
