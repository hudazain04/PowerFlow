<?php

namespace App\Services\Admin;

use App\ApiHelper\ApiResponse;
use App\DTOs\SubscribedGeneratorDTO;
use App\DTOs\SubscriptionDTO;
use App\Http\Resources\SubscribedGeneratorResource;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;

class PowerGeneratorService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected PowerGeneratorRepositoryInterface $powerGeneratorRepository,
    )
    {
        //
    }

    public function getForPlan(int $plan_id ,  array $filters)
    {
        $generators=$this->powerGeneratorRepository->getForPlan($plan_id,$filters);
        $generatorsDTOs=$generators->map(function ($generator){
            $generatorDTO=SubscribedGeneratorDTO::fromModel($generator);
            $generatorDTO->phone=$generator->user->phone_number;
            $generatorDTO->expired_at=$generator->subscriptions->first()->start_time->addMonths($generator->subscriptions->first()->period);
//            dd(gettype($generator->subscriptions->first()->start_time));
            return $generatorDTO;
        });
        return $this->success(SubscribedGeneratorResource::collection($generatorsDTOs),__('messages.success'));
    }
}
