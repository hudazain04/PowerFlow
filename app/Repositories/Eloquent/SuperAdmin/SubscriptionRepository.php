<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\Models\Subscription as SubscriptionModel;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;


class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function getAllWithPlan(): Collection
    {
        return SubscriptionModel::selectRaw('plan_prices.plan_id, COUNT(*) as count')
            ->join('plan_prices', 'subscriptions.planPrice_id', '=', 'plan_prices.id')
            ->groupBy('plan_prices.plan_id')->get();
    }

    public function getSubscriptionsWithMonths(int $year) : Collection
    {
        $startOfYear = Carbon::create($year)->startOfYear();
        $endOfYear = Carbon::create($year)->endOfYear();

        return SubscriptionModel::selectRaw('plan_prices.plan_id, MONTH(subscriptions.created_at) as month, COUNT(*) as count')
        ->join('plan_prices', 'subscriptions.planPrice_id', '=', 'plan_prices.id')
        ->whereBetween('subscriptions.created_at', [$startOfYear, $endOfYear])
        ->groupBy('plan_prices.plan_id', 'month')
        ->get();

    }
}
