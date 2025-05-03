<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\ApiHelper\HelperMethods;
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
        $planPrice= $this->planPricePriceRepository->all($plan_id);
        return $this->success(PlanPriceResource::collection($planPrice),__('messages.success'));

    }

    public function create(CreatePlanPriceRequest $request)
    {
        $planPriceDTO=planPriceDTO::fromRequest($request);
//        dd($planPriceDTO);
        $plan=$this->planRepository->find($planPriceDTO->plan_id);
        $monthlyPrice=$plan->monthlyPrice;
        $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
        $planPriceDTO->price=$price;
        $planPrice= $this->planPricePriceRepository->create($planPriceDTO);
        return $this->success(PlanPriceResource::make($planPrice),__('planPrice.create'),ApiCode::CREATED);
    }

    public function update(int $id , CreatePlanPriceRequest $request)
    {
        $planPriceDTO=planPriceDTO::fromRequest($request);
        $plan=$this->planRepository->find($planPriceDTO->plan_id);
        $monthlyPrice=$plan->monthlyPrice;
        $price=$this->calculateTotalPrice($monthlyPrice,$planPriceDTO->discount,$planPriceDTO->period);
        $planPriceDTO->price=$price;
        $planPrice=$this->planPricePriceRepository->update($id,$planPriceDTO);
        return $this->success(PlanPriceResource::make($planPrice),__('planPrice.update'));
    }

    public function find(int $id)
    {
        $planPrice=$this->planPricePriceRepository->find($id);
        return $this->success(PlanPriceResource::make($planPrice),__('messages.success'));

    }

    public function delete(int $id)
    {
        $this->planPricePriceRepository->delete($id);
        return $this->success(__('planPrice.delete'));
    }

}
