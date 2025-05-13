<?php

namespace App\Http\Controllers;

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

}
