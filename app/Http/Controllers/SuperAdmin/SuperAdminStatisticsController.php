<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\StatisticsService;
use Illuminate\Http\Request;

class SuperAdminStatisticsController extends Controller
{
    public function __construct(protected StatisticsService $statisticsService)
    {
    }

    public function homeStatistics()
    {
        return $this->statisticsService->homeStatistics();
    }

    public function getSubscriptionDistributionByPlan(int $year)
    {
        return $this->statisticsService->getSubscriptionDistributionByPlan($year);
    }

    public function subscriptionsPerPlans()
    {
        return $this->statisticsService->subscriptionsPerPlans();
    }

    public function subscriptionRequestsPerPlans()
    {
        return $this->statisticsService->subscriptionRequestsPerPlans();
    }

    public function topRequestedPlan()
    {
        return $this->statisticsService->topRequestedPlan();
    }

    public function visitLandingPage()
    {
        return $this->statisticsService->visitLandingPage();
    }

    public function getTotalVisitors()
    {
        return $this->statisticsService->getTotalVisitors();
    }

    public function getAvgDailyVisits()
    {
        return $this->statisticsService->getAvgDailyVisits();
    }

    public function planStatistics(int $plan_id)
    {
        return $this->statisticsService->planStatistics($plan_id);
    }

    public function distributionOfPlanPricesRequests(int $plan_id)
    {
        return $this->statisticsService->distributionOfPlanPricesRequests($plan_id);
    }
}
