<?php

namespace App\Http\Controllers\SuperAdmin;

use App\DTOs\SubscriptionRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest\CreateSubscriptionRequestRequest;
use App\Services\SuperAdmin\SubscriptionRequestService;
use App\Types\SubscriptionTypes;
use App\Types\UserTypes;
use Illuminate\Http\Request;

class SubscriptionRequestController extends Controller
{
    public function __construct(
        protected SubscriptionRequestService $subscriptionRequestService,
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
        if (! $request->user()->hasRole('admin')){
            $requestDTO->type=SubscriptionTypes::NewPlan;
        }
        return $this->subscriptionRequestService->store($requestDTO);
    }

    public function getAll(Request $request)
    {
        return $this->subscriptionRequestService->getAll($request);
    }

    public function approve(int $requestId)
    {
        return $this->subscriptionRequestService->approve($requestId);
    }

    public function reject(int $requestId)
    {
        return $this->subscriptionRequestService->reject($requestId);

    }

    public function renew()
    {

    }

}
