<?php

namespace App\Http\Controllers;

use App\Services\Admin\PowerGeneratorService;
use Illuminate\Http\Request;

class PowerGeneratorController extends Controller
{
    public function __construct(
        protected PowerGeneratorService $powerGeneratorService,
    )
    {
    }

    public function getForPlan(int $plan_id , Request $request)
    {
        return $this->powerGeneratorService->getForPlan($plan_id, $request->query());
    }
}
