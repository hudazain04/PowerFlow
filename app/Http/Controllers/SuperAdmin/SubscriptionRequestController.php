<?php

namespace App\Http\Controllers\SuperAdmin;

use App\ApiHelper\ApiResponse;
use App\DTOs\SubscriptionRequestDTO;
use App\Events\TopRequestedPlanEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest\CreateSubscriptionRequestRequest;
use App\Http\Requests\SubscriptionRequest\RenewRequest;
use App\Services\SuperAdmin\StatisticsService;
use App\Services\SuperAdmin\SubscriptionRequestService;
use App\Types\SubscriptionTypes;
use App\Types\UserTypes;
use Illuminate\Http\Request;

class SubscriptionRequestController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected SubscriptionRequestService $subscriptionRequestService,
        protected StatisticsService $statisticsService,
    )
    {
    }

    public function getLastFive()
    {
        return $this->subscriptionRequestService->getLastFive();
    }

    public function store(CreateSubscriptionRequestRequest $request)
    {
        $requestDTO=SubscriptionRequestDTO::fromRequest($request);
        $requestDTO->user_id=$request->user()->id;
        $requestDTO->type=SubscriptionTypes::NewPlan;

        $topRequestedPlan=$this->statisticsService->topRequestedPlan();
        $response= $this->subscriptionRequestService->store($requestDTO);
        event(new TopRequestedPlanEvent($topRequestedPlan));
        return $response;
    }

    public function getAll(Request $request)
    {
        return $this->subscriptionRequestService->getAll($request);
    }

    public function approve(int $requestId)
    {
        $response=$this->subscriptionRequestService->approve($requestId);
        return $this->success(null, __('subscriptionRequest.approve'));
    }

    public function reject(int $requestId)
    {
        return $this->subscriptionRequestService->reject($requestId);

    }


}
