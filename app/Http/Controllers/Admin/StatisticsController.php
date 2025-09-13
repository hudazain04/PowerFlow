<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Counter;
use App\Models\ElectricalBox;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function getOverviewStatistics(Request $request)
    {
        $generatorId = auth()->user()->powerGenerator->id;

        // Total counts
        $totalAreas = Area::where('generator_id', $generatorId)->count();
        $totalBoxes = ElectricalBox::where('generator_id', $generatorId)->count();
        $totalCounters = Counter::where('generator_id', $generatorId)->count();
        $totalEmployees = Employee::where('generator_id', $generatorId)->count();
        $totalClients = User::whereHas('counters', function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })->count();

        // Active counters (with recent activity)
        $activeCounters = Counter::where('generator_id', $generatorId)
            ->whereHas('spendings', function($query) {
                $query->where('date', '>=', Carbon::now()->subDays(30));
            })
            ->count();

        // Recent payments
        $recentPayments = Payment::whereHas('counter', function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('amount');

        return   ApiResponses::success([
            [
                'total_areas' => $totalAreas,
                'total_boxes' => $totalBoxes,
                'total_counters' => $totalCounters,
                'total_employees' => $totalEmployees,
                'total_clients' => $totalClients,
                'active_counters' => $activeCounters,
                'recent_payments' => $recentPayments,
            ],'statistics',ApiCode::OK


        ]);
    }


    public function getCounterDetails(Request $request, $counterId)
    {
        $counter = Counter::with('user')
            ->withSum('spendings', 'consume')
            ->withSum('payments', 'amount')
            ->findOrFail($counterId);

        // Recent spending (last 7 days)
        $recentSpending = Spending::where('counter_id', $counterId)
            ->where('date', '>=', Carbon::now()->subDays(7))
            ->orderBy('date', 'desc')
            ->get();

        // Recent payments (last 30 days)
        $recentPayments = Payment::where('counter_id', $counterId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get();

        return ApiResponses::success([
            [
                'counter' => $counter,
                'total_consumption' => $counter->spendings_sum_consume,
                'total_payments' => $counter->payments_sum_amount,
                'recent_spending' => $recentSpending,
                'recent_payments' => $recentPayments,
            ], 'Counter details retrieved successfully', ApiCode::OK
        ]);
    }
    public function getBoxDetails(Request $request, $boxId)
    {
        $box = ElectricalBox::with(['counters.user'])
            ->withCount('counters')
            ->findOrFail($boxId);

        // Calculate box utilization percentage
        $utilization = $box->counters_count > 0
            ? ($box->counters_count / $box->capacity) * 100
            : 0;

        return ApiResponses::success([
             [
                'box' => $box,
                'counters_count' => $box->counters_count,
                'capacity' => $box->capacity,
                'available_slots' => $box->capacity - $box->counters_count,
            ], 'Box details retrieved successfully', ApiCode::OK
        ]);
    }
    public function getAreaDetails(Request $request, $areaId)
    {
        $area = Area::withCount('electricalbox')
            ->findOrFail($areaId);

        // Get boxes in this area
        $boxes = ElectricalBox::whereHas('areas', function($query) use ($areaId) {
            $query->where('area_id', $areaId);
        })
            ->withCount('counters')
            ->get();

        return ApiResponses::success([
           [
                'area' => $area,
                'boxes_count' => $area->electricalbox_count,
                'boxes' => $boxes,
            ], 'Area details retrieved successfully', ApiCode::OK
        ]);
    }
    public function getTotalCounts(Request $request)
    {
        $generatorId = auth()->user()->powerGenerator->id;

        $totals = [
            'areas' => Area::where('generator_id', $generatorId)->count(),
            'boxes' => ElectricalBox::where('generator_id', $generatorId)->count(),
            'counters' => Counter::where('generator_id', $generatorId)->count(),
            'clients' => User::whereHas('counters', function($query) use ($generatorId) {
                $query->where('generator_id', $generatorId);
            })->count(),
        ];

        return ApiResponses::success([
             $totals, 'Total counts retrieved successfully', ApiCode::OK
        ]);
    }



    public function getRecentActivities(Request $request)
    {
        $generatorId = auth()->user()->powerGenerator->id;

        // Recent payments (last 10)
        $recentPayments = Payment::whereHas('counter', function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })
            ->with('counter.user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent spending (last 10)
        $recentSpending = Spending::whereHas('counter', function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })
            ->with('counter.user')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return ApiResponses::success([
            [
                'recent_payments' => $recentPayments,
                'recent_spending' => $recentSpending,
            ], 'Recent activities retrieved successfully', ApiCode::OK
        ]);
    }


}
