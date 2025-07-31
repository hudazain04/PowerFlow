<?php

namespace App\Http\Controllers;

use App\Services\SuperAdmin\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
    )
    {
    }

    public function cancel(Request $request)
    {
        return $this->subscriptionService->cancel($request->user());
    }

}
