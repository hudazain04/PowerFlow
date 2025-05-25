<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiResponse;
use App\DTOs\PlanDTO;
use App\DTOs\PlanPriceDTO;
use App\DTOs\PowerGeneratorDTO;
use App\DTOs\SubscriptionRequestDTO;
use App\DTOs\UserDTO;
use App\Http\Resources\SubscriptionRequestResource;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;

class SubscriptionRequestService
{
    use ApiResponse;

    public function __construct(
        protected SubscriptionRequestRepositoryInterface $subscriptionRequestRepository,
    )
    {
    }

    public function getLastFive()
    {
        $subscriptionRequests=$this->subscriptionRequestRepository->getLastFive();
        $subscriptionRequestsDTOs=$subscriptionRequests->map(function ($subscriptionRequest){
            $subscriptionRequestDTO=SubscriptionRequestDTO::fromModel($subscriptionRequest);
            $user=$subscriptionRequest->user;
            $subscriptionRequestDTO->user=UserDTO::fromModel($user);
//            dd($user->powerGenerator);
            $subscriptionRequestDTO->powerGenerator=PowerGeneratorDTO::fromModel($user->powerGenerator);
            $planPrice=$subscriptionRequest->planPrice;
            $subscriptionRequestDTO->planPrice=PlanPriceDTO::fromModel($planPrice);
           $subscriptionRequestDTO->plan=PlanDTO::fromModel($planPrice->plan);
           return $subscriptionRequestDTO;
        });

        return $this->success(SubscriptionRequestResource::collection($subscriptionRequestsDTOs),__('messages.success'));
    }
}
