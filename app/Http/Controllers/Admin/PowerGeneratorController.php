<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function getAll(Request $request)
    {
        return $this->powerGeneratorService->getAll( $request->query());

    }
}
