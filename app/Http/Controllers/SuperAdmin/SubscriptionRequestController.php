<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\SubscriptionRequestService;
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
}
