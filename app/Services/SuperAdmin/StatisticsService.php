<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\VisitorRepositoryInterface;
use App\Repositories\interfaces\UserRepositoryInterface;
use App\Types\SubscriptionExpirationTypes;
use App\Types\SubscriptionTypes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

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
        protected VisitorRepositoryInterface $visitorRepository,
        protected PlanPriceRepositoryInterface $planPriceRepository,

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

    public function visitLandingPage()
    {
        $timestamp = Carbon::now()->toDateTimeString();
        $key = 'visits:' . Carbon::now()->format('Y-m-d');
        Redis::rpush($key, $timestamp);
        return $this->success(null,__('messages.success'));
    }

    public function getTotalVisitors()
    {
        $visitors=$this->visitorRepository->count();
        return  $this->success($visitors,__('messages.success'));
    }

    public function getAvgDailyVisits()
    {
        $dailyAvg=$this->visitorRepository->dailyAvg();
        return $this->success($dailyAvg,__('messages.success'));
    }

    public function planStatistics(int $plan_id)
    {
        $requests=$this->subscriptionRequestRepository->getRequestsCountForPlan($plan_id);
        $renewalRequests=$this->subscriptionRequestRepository->getRequestsForPlan($plan_id,SubscriptionTypes::Renew)->count();
        $activeSubscriptions=$this->subscriptionRepository->getSubscriptionsForPlan($plan_id,SubscriptionExpirationTypes::Active)->count();
        $expiredSubscriptions=$this->subscriptionRepository->getSubscriptionsForPlan($plan_id,SubscriptionExpirationTypes::Expired)->count();
        $data=[
            'requests'=>$requests,
            'renewalRequests'=>$renewalRequests,
            'activeSubscriptions'=>$activeSubscriptions,
            'expiredSubscriptions'=>$expiredSubscriptions,
        ];
        return $this->success($data,__('messages.success'));
    }

    public function distributionOfPlanPricesRequests(int $plan_id)
    {
        $planPrices=$this->planPriceRepository->distributionOfRequests($plan_id);
        $data=[];
        foreach ($planPrices as $planPrice)
        {
            array_push($data,[
                'id'=>$planPrice->id,
               'period'=> $planPrice->period,
               'count'=>$planPrice->subscription_requests_count,
            ]);

        }
        return $this->success($data,__('messages.success'));
    }


}
