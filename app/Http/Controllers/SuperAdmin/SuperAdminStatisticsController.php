<?php

namespace App\Http\Controllers\SuperAdmin;


use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;

use App\ApiHelper\ApiResponse;

use App\Http\Controllers\Controller;
use App\Http\Resources\PowerGeneratorResource;
use App\Models\PowerGenerator;
use App\Services\Admin\AreaService;
use App\Services\Admin\CounterService;
use App\Services\Admin\ElectricalBoxService;
use App\Services\Admin\EmployeeService;
use App\Services\SuperAdmin\GeneratorRequestService;
use App\Services\SuperAdmin\PlanService;
use App\Services\SuperAdmin\StatisticsService;
use Illuminate\Http\Request;

class SuperAdminStatisticsController extends Controller
{

    public function __construct(
        protected StatisticsService $statisticsService,
        private AreaService $service,
        private ElectricalBoxService $boxService,
        private CounterService $counterService,
        private EmployeeService $employeeService,
        private PlanService $planService,
        private GeneratorRequestService $generatorRequestService,
    )
    {
    }

    use ApiResponse;


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
        $topRequestedPlan=$this->statisticsService->topRequestedPlan();
        return $this->success(['topRequestedPlan'=>$topRequestedPlan],__('messages.success'));
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
    public function getGeneratorStatistics(int $generator_id)
    {
        $features = $this->planService->getPlanFeatureValues($generator_id)
            ->mapWithKeys(function ($item) {
                return [$item->key => $item->value];
            });
        return ApiResponses::success([
                'boxes' => [
                    'used' => $this->boxService->getBoxes($generator_id),
                    'total' => $features['boxes_count'] ?? $generator->plan->box_limit ?? 0
                ],
                'electrical_meters' => [
                    'used' => $this->counterService->getCounters($generator_id),
                    'total' =>$features['counters_count'] ?? $generator->plan->box_limit ?? 0
                ],
                'total_consumption' => $this->counterService->Consumption($generator_id),

                    'users' => [
                        'used' => $this->counterService->getUsers($generator_id),
                        'total' => $features['users_count'] ?? $generator->plan->box_limit ?? 0
                    ],
                    'areas' => [
                        'used' => $this->service->getAreas($generator_id),
                        'total' => $features['areas_count'] ?? $generator->plan->box_limit ?? 0
                    ],
                    'employees' => [
                        'used' => $this->employeeService->getEmp($generator_id),
                        'total' => $features['employee_count'] ?? $generator->plan->box_limit ?? 0
                    ]

            ],'success',ApiCode::OK);


    }
    public function getGenInfo($generator_id){
        $data=$this->generatorRequestService->getGenInfo($generator_id);
        return ApiResponses::success(PowerGeneratorResource::make($data),__('messages.success'),ApiCode::OK);
    }
}
