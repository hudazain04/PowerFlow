<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;
use App\Repositories\interfaces\UserRepositoryInterface;
use Carbon\Carbon;

class StatisticsService
{

    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected PowerGeneratorRepositoryInterface $powerGeneratorRepository,
        protected SubscriptionRequestRepositoryInterface $subscriptionRequestRepository,
        protected SubscriptionRepositoryInterface $subscriptionRepository,
        protected PlanRepositoryInterface $planRepository,
    )
    {
        //
    }

    public function homeStatistics()
    {
        $accounts=$this->userRepository->count();
        $blockedAccounts=$this->userRepository->blockedCount();
        $powerGenerators=$this->powerGeneratorRepository->count();
        $subscriptionRequests=$this->subscriptionRequestRepository->count();
        $data=[
            'accounts'=>$accounts,
            'blockedAccounts'=>$blockedAccounts,
            'powerGenerators'=>$powerGenerators,
            'subscriptionRequests'=>$subscriptionRequests,
        ];
        return $this->success($data,__('messages.success'),ApiCode::OK);

    }

    public function getSubscriptionDistributionByPlan(int $year)
    {
//        dd(5);
        $subscriptions=$this->subscriptionRepository->getSubscriptionsWithMonths($year);
//        dd($subscriptions->toArray());
        $plans=$this->planRepository->getAllByColumns(['id','name']);
        $data=$plans->map(function ($plan) use ($subscriptions) {
            $monthlyData = collect(range(1, 12))->map(function ($month) use ($plan, $subscriptions) {
                $count = $subscriptions
                        ->where('plan_id', $plan->id)
                        ->where('month', $month)
                        ->first()
                        ->count ?? 0;

                return [
                    'month' => Carbon::create()->month($month)->format('M'),
                    'count' => $count,
                ];
            });

            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'monthly_data' => $monthlyData,
            ];
        });
        return $this->success($data,__('messages.success'));
    }

    public function subscriptionsPerPlans()
    {
        $subscriptions=$this->subscriptionRepository->getAllWithPlan();
        $plans = $this->planRepository->getAllByColumns(['id','name']);

        $data= $plans->map(function ($plan) use ($subscriptions) {
            $subscriptionCount = $subscriptions->where('plan_id', $plan->id)->first();
            $plan->subscription_count = $subscriptionCount ? $subscriptionCount->count : 0;
            return $plan;
        });
        return $this->success($data,__('messages.success'));
    }

    public function subscriptionRequestsPerPlans()
    {

        $subscriptionRequests=$this->subscriptionRequestRepository->getAllWithPlan();
        $plans = $this->planRepository->getAllByColumns(['id','name']);

        $data= $plans->map(function ($plan) use ($subscriptionRequests) {
            $subscriptionRequestCount = $subscriptionRequests->where('plan_id', $plan->id)->first();
            $plan->subscriptionRequestCount = $subscriptionRequestCount ? $subscriptionRequestCount->count : 0;
            return $plan;
        });
        return $this->success($data,__('messages.success'));
    }
    public function topRequestedPlan()
    {
        $subscriptionRequests=$this->subscriptionRequestRepository->getAllWithPlan();
        $plans = $this->planRepository->getAllByColumns(['id','name']);

        $data= $plans->map(function ($plan) use ($subscriptionRequests) {
            $subscriptionRequestCount = $subscriptionRequests->where('plan_id', $plan->id)->first();
            $plan->subscriptionRequestCount = $subscriptionRequestCount ? $subscriptionRequestCount->count : 0;
            return $plan;
        });
        $topRequestedPlan = $data->sortByDesc('subscriptionRequestCount')->first();
        return $this->success($topRequestedPlan,__('messages.success'));

    }

}
