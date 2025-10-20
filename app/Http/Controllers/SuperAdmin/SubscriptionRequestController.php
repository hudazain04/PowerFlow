<?php

namespace App\Http\Controllers\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\SubscriptionRequestDTO;
use App\Events\TopRequestedPlanEvent;
use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest\RejectRequest;
use App\Http\Requests\SubscriptionRequest\CreateSubscriptionRequestRequest;
use App\Http\Requests\SubscriptionRequest\RenewRequest;
use App\Services\SuperAdmin\StatisticsService;
use App\Services\SuperAdmin\SubscriptionRequestService;
use App\Types\SubscriptionTypes;
use App\Types\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
        try {
            $requestDTO=SubscriptionRequestDTO::fromRequest($request);
            $requestDTO->user_id=$request->user()->id;
            $requestDTO->type=SubscriptionTypes::NewPlan;

            $response= $this->subscriptionRequestService->store($requestDTO);
            $topRequestedPlan=$this->statisticsService->topRequestedPlan();
            event(new TopRequestedPlanEvent($topRequestedPlan));
            DB::commit();
            return $response;

        }
        catch (\Throwable $exception)
        {
            DB::rollBack();
            throw new ErrorException(__('messages.error.serverError'),ApiCode::INTERNAL_SERVER_ERROR);
        }

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

    public function reject(RejectRequest $request ,int $requestId)
    {
        $admin_notes=$request->admin_notes;
        return $this->subscriptionRequestService->reject($admin_notes,$requestId);

    }


}
