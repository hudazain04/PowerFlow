<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\Models\Subscription as SubscriptionModel;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Types\SubscriptionExpirationTypes;
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

    public function getSubscriptionsForPlan(int $plan_id, ?string $type): Collection
    {
        $subscriptions=SubscriptionModel::filter($type)->whereRelation('planPrice','plan_id',$plan_id)->get();
        return $subscriptions;
    }

    public function create(array $data) : SubscriptionModel
    {
        $subscription=SubscriptionModel::create($data);
        return  $subscription;
    }

    public function getLastForUser(int $user_id): SubscriptionModel
    {
        $subscription=SubscriptionModel::where('user_id',$user_id)->get()
            ->filter(function ($subscription){
                return $subscription->start_time->addMonths($subscription->period)->gt(now());

            })
            ->sortByDesc('start_time')->fisrt();
        return $subscription;
    }

    public function update(SubscriptionModel $subscription, array $data): SubscriptionModel
    {
       $subscription->update($data);
       $subscription->save();
       return  $subscription;
    }

    public function softDelete(SubscriptionModel $subscription): bool
    {
        return  $subscription->delete();
    }
}

