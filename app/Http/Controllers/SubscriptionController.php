<?php

namespace App\Http\Controllers;

use App\DTOs\SubscriptionRequestDTO;
use App\Events\TopRequestedPlanEvent;
use App\Http\Requests\Subscription\UpgradeRequest;
use App\Http\Requests\SubscriptionRequest\RenewRequest;
use App\Services\SuperAdmin\StatisticsService;
use App\Services\SuperAdmin\SubscriptionService;
use App\Types\SubscriptionTypes;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
        protected StatisticsService  $statisticsService,
    )
    {
    }

    public function cancel(Request $request)
    {
        return $this->subscriptionService->cancel($request->user());
    }

    public function renew(RenewRequest $request)
    {
        $subscriptionRequestDTO=SubscriptionRequestDTO::fromRequest($request);
        $subscriptionRequestDTO->type=SubscriptionTypes::Renew;
        $subscriptionRequestDTO->user_id=$request->user()->id;
        $topRequestedPlan=$this->statisticsService->topRequestedPlan();
        $response= $this->subscriptionService->renew($subscriptionRequestDTO);
        event(new TopRequestedPlanEvent($topRequestedPlan));
        return $response;
    }

    public function upgrade(RenewRequest $request)
    {
        $subscriptionRequestDTO=SubscriptionRequestDTO::fromRequest($request);
        $subscriptionRequestDTO->user_id=$request->user()->id;
        $subscriptionRequestDTO->type=SubscriptionTypes::Renew;
        return  $this->subscriptionService->upgrade($subscriptionRequestDTO);
    }
}

