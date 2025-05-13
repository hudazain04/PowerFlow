<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\Models\SubscriptionRequest as SubscriptionRequestModel;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;
use Illuminate\Support\Collection;

class SubscriptionRequestRepository implements SubscriptionRequestRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function count(): int
    {
        return SubscriptionRequestModel::count();
    }

    public function getLastFive(): Collection
    {
        return SubscriptionRequestModel::latest()->take(5)->get();
    }

    public function getAllWithPlan(): Collection
    {
        return SubscriptionRequestModel::selectRaw('plan_prices.plan_id, COUNT(*) as count')
            ->join('plan_prices', 'subscription_requests.planPrice_id', '=', 'plan_prices.id')
            ->groupBy('plan_prices.plan_id')->get();
    }
}
