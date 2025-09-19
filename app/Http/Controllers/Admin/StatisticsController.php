<?php

namespace App\Http\Controllers\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\CounterResource;
use App\Http\Resources\SpendingPaymentRersource;
use App\Http\Resources\SpendingResource;
use App\Models\Area;
use App\Models\Counter;
use App\Models\ElectricalBox;
use App\Models\Employee;
use App\Models\GeneratorSetting;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\User;
use App\Types\DaysOfWeek;
use App\Types\SpendingTypes;
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
        $totalClients = User::whereHas('counters', function ($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })->count();

        // Active counters (with recent activity)
        $activeCounters = Counter::where('generator_id', $generatorId)
            ->whereHas('spendings', function ($query) {
                $query->where('date', '>=', Carbon::now()->subDays(30));
            })
            ->count();

        // Recent payments
        $recentPayments = Payment::whereHas('counter', function ($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('amount');

        return ApiResponses::success([
            [
                'total_areas' => $totalAreas,
                'total_boxes' => $totalBoxes,
                'total_counters' => $totalCounters,
                'total_employees' => $totalEmployees,
                'total_clients' => $totalClients,
                'active_counters' => $activeCounters,
                'recent_payments' => $recentPayments,
            ],
            'statistics',
            ApiCode::OK


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

        return ApiResponses::success(
            [

                'counter' => CounterResource::make($counter),
                'total_consumption' => $counter->spendings_sum_consume ?? 0,
                'total_payments' => $counter->payments_sum_amount ?? 0,
                'recent_spending' => SpendingResource::collection($recentSpending),
                'recent_payments' => SpendingPaymentRersource::collection($recentPayments),
            ],
            'Counter details retrieved successfully',
            ApiCode::OK
        );
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
            ],
            'Box details retrieved successfully',
            ApiCode::OK
        ]);
    }

    public function getAreaDetails(Request $request, $areaId)
    {
        $area = Area::withCount('electricalbox')
            ->findOrFail($areaId);

        // Get boxes in this area
        $boxes = ElectricalBox::whereHas('areas', function ($query) use ($areaId) {
            $query->where('area_id', $areaId);
        })
            ->withCount('counters')
            ->get();

        return ApiResponses::success([
            [
                'area' => $area,
                'boxes_count' => $area->electricalbox_count,
                'boxes' => $boxes,
            ],
            'Area details retrieved successfully',
            ApiCode::OK
        ]);
    }

    public function getTotalCounts(Request $request)
    {
        $generatorId = auth()->user()->powerGenerator->id;

        $totals = [
            'areas' => Area::where('generator_id', $generatorId)->count(),
            'boxes' => ElectricalBox::where('generator_id', $generatorId)->count(),
            'counters' => Counter::where('generator_id', $generatorId)->count(),
            'clients' => User::whereHas('counters', function ($query) use ($generatorId) {
                $query->where('generator_id', $generatorId);
            })->count(),
        ];

        return ApiResponses::success([
            $totals,
            'Total counts retrieved successfully',
            ApiCode::OK
        ]);
    }


    public function getRecentActivities(Request $request)
    {
        $generatorId = auth()->user()->powerGenerator->id;

        // Recent payments (last 10)
        $recentPayments = Payment::whereHas('counter', function ($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })
            ->with('counter.user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent spending (last 10)
        $recentSpending = Spending::whereHas('counter', function ($query) use ($generatorId) {
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
            ],
            'Recent activities retrieved successfully',
            ApiCode::OK
        ]);
    }


//    public function getDashboardOverview(Request $request)
//    {
//        $generatorId = auth()->user()->powerGenerator->id;
//
//        // 1. Count of clients in generator
//        $clientCount = User::whereHas('counters', function ($query) use ($generatorId) {
//            $query->where('generator_id', $generatorId);
//        })->count();
//
//        // 2. Count of boxes in generator
//        $boxCount = ElectricalBox::where('generator_id', $generatorId)->count();
//
//        // 3. Count of counters in generator
//        $counterCount = Counter::where('generator_id', $generatorId)->count();
//
//        // 4. Counters with most consumption (top 5)
//        $topConsumingCounters = Counter::with('user')
//            ->where('generator_id', $generatorId)
//            ->withSum('spendings', 'consume')
//            ->orderBy('spendings_sum_consume', 'desc')
//            ->limit(5)
//            ->get()
//            ->map(function ($counter) {
//                return [
//                    'id' => $counter->id,
//                    'number' => $counter->number,
//                    'user_name' => $counter->user ? $counter->user->first_name . ' ' . $counter->user->last_name : 'N/A',
//                    'total_consumption' => $counter->spendings_sum_consume ?? 0,
//                    'user_id' => $counter->user_id
//                ];
//            });
//
//        // 5. Counters with least consumption (bottom 5, excluding zero)
//        $leastConsumingCounters = Counter::with('user')
//            ->where('generator_id', $generatorId)
//            ->withSum('spendings', 'consume')
//            ->having('spendings_sum_consume', '>', 0) // Exclude counters with no consumption
//            ->orderBy('spendings_sum_consume', 'asc')
//            ->limit(5)
//            ->get()
//            ->map(function ($counter) {
//                return [
//                    'id' => $counter->id,
//                    'number' => $counter->number,
//                    'user_name' => $counter->user ? $counter->user->first_name . ' ' . $counter->user->last_name : 'N/A',
//                    'total_consumption' => $counter->spendings_sum_consume ?? 0,
//                    'user_id' => $counter->user_id
//                ];
//            });
//
//        // 6. Additional useful statistics
//        $activeClients = User::whereHas('counters', function ($query) use ($generatorId) {
//            $query->where('generator_id', $generatorId);
//        })
//            ->whereHas('counters.spendings', function ($query) {
//                $query->where('date', '>=', now()->subDays(30));
//            })
//            ->count();
//
//        $totalConsumption = Spending::whereHas('counter', function ($query) use ($generatorId) {
//            $query->where('generator_id', $generatorId);
//        })->sum('consume');
//
//        $averageConsumption = $counterCount > 0 ? $totalConsumption / $counterCount : 0;
//
//
//        $generatorId = auth()->user()->powerGenerator->id;
//
//        // Get generator settings
//        $generatorSetting = GeneratorSetting::where('generator_id', $generatorId)->first();
//
////        if (!$generatorSetting || $generatorSetting->spendingType !== SpendingTypes::Before) {
////            return response()->json([
////                'message' => 'Generator is not configured for Before payment type',
////                'data' => []
////            ], 200);
////        }
//
//        $counters = Counter::with(['user', 'spendings' => function($query) {
//            $query->orderBy('date', 'desc')->limit(1);
//        }])
//            ->with(['payments' => function($query) {
//                $query->orderBy('date', 'desc')->limit(1);
//            }])
//            ->where('generator_id', $generatorId)
//            ->get();
//
//        $result = [
//            '75_percent' => [],
//            '90_percent' => [],
//            'cut_off' => []
//        ];
//
//        foreach ($counters as $counter) {
//            $latestSpending = $counter->spendings->first();
//            $latestPayment = $counter->payments->first();
//
//            if (!$latestSpending || !$latestPayment) {
//                continue;
//            }
//
//            // Calculate percentage of consumption compared to payment
//            $percentage = ($latestSpending->consume / $latestPayment->amount) * 100;
//
//            if ($percentage >= 90) {
//                $result['cut_off'][] = [
//                    'counter' => $counter->count(),
////                    'percentage' => round($percentage, 2),
////                    'latest_spending' => $latestSpending,
////                    'latest_payment' => $latestPayment
//                ];
//            } elseif ($percentage >= 75) {
//                $result['75_percent'][] = [
//                    'counter' => $counter->count(),
////                    'percentage' => round($percentage, 2),
////                    'latest_spending' => $latestSpending,
////                    'latest_payment' => $latestPayment
//                ];
//            }
//        }
//
////        return response()->json([
////            'data' => $result,
////            'message' => 'Counters due for before payment retrieved successfully',
////            'code' => 200
////        ]);
//
//
//
//            $data = [
//                'client_count' => $clientCount,
//                'box_count' => $boxCount,
//                'counter_count' => $counterCount,
////                'total_consumption' => round($totalConsumption, 2),
////                'average_consumption' => round($averageConsumption, 2),
//                'top_consuming_counters' => $topConsumingCounters,
//                'least_consuming_counters' => $leastConsumingCounters,
//                'pay'=>$result
//            ];
//
//        return ApiResponses::success($data,'statistics retrieved',ApiCode::OK);
//    }

    public function getDashboardOverview(Request $request)
    {
        $generatorId = auth()->user()->powerGenerator->id;

        // 1. Basic counts
        $clientCount = User::whereHas('counters', function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })->count();

        $boxCount = ElectricalBox::where('generator_id', $generatorId)->count();
        $counterCount = Counter::where('generator_id', $generatorId)->count();

        // 2. Consumption statistics
        $totalConsumption = Spending::whereHas('counter', function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })->sum('consume');

        $averageConsumption = $counterCount > 0 ? $totalConsumption / $counterCount : 0;

        // 3. Top and least consuming counters
        $topConsumingCounters = Counter::with('user')
            ->where('generator_id', $generatorId)
            ->withSum('spendings', 'consume')
            ->orderBy('spendings_sum_consume', 'desc')
            ->limit(5)
            ->get()
            ->map(function($counter) {
                return [
                    'id' => $counter->id,
                    'number' => $counter->number,
                    'user_name' => $counter->user ? $counter->user->first_name . ' ' . $counter->user->last_name : 'N/A',
                    'total_consumption' => $counter->spendings_sum_consume ?? 0,
                ];
            });

        $leastConsumingCounters = Counter::with('user')
            ->where('generator_id', $generatorId)
            ->withSum('spendings', 'consume')
            ->having('spendings_sum_consume', '>', 0)
            ->orderBy('spendings_sum_consume', 'asc')
            ->limit(5)
            ->get()
            ->map(function($counter) {
                return [
                    'id' => $counter->id,
                    'number' => $counter->number,
                    'user_name' => $counter->user ? $counter->user->first_name . ' ' . $counter->user->last_name : 'N/A',
                    'total_consumption' => $counter->spendings_sum_consume ?? 0,
                ];
            });


        $dueCounters = $this->getDueCounters($generatorId);

//        return response()->json([
            $data = [

                'client_count' => $clientCount,
                'box_count' => $boxCount,
                'counter_count' => $counterCount,

                // Consumption statistics
//                'total_consumption' => round($totalConsumption, 2),
//                'average_consumption' => round($averageConsumption, 2),

                'top_consuming_counters' => $topConsumingCounters,
                'least_consuming_counters' => $leastConsumingCounters,

                // Due counters
                'counters_should_pay' => $dueCounters,
            ];
//            'message' => 'Dashboard overview statistics retrieved successfully',
//            'code' => 200
//        ]);
        return ApiResponses::success($data,'statistics retrieved',ApiCode::OK);
    }

    private function getDueCounters($generatorId)
    {

        $generatorSetting = GeneratorSetting::where('generator_id', $generatorId)->first();

        if (!$generatorSetting) {
            return [
                'before_payment' => [
                    '75_percent_count' => 0,
                    '90_percent_count' => 0,
                    'cut_off_count' => 0
                ],
                'after_payment' => [
                    'payment_day_count' => 0,
                    'day_before_count' => 0
                ]
            ];
        }

        $result = [];


        if ($generatorSetting->spendingType === SpendingTypes::Before) {
            $counters = Counter::with(['spendings' => function($query) {
                $query->orderBy('date', 'desc')->limit(1);
            }])
                ->with(['payments' => function($query) {
                    $query->orderBy('date', 'desc')->limit(1);
                }])
                ->where('generator_id', $generatorId)
                ->get();

            $count75 = 0;
            $count90 = 0;
            $countCutoff = 0;

            foreach ($counters as $counter) {
                $latestSpending = $counter->spendings->first();
                $latestPayment = $counter->payments->first();

                if (!$latestSpending || !$latestPayment) {
                    continue;
                }

                $percentage = ($latestSpending->consume / $latestPayment->amount) * 100;

                if ($percentage >= 90) {
                    $countCutoff++;
                } elseif ($percentage >= 75) {
                    $count75++;
                }
            }

//            $result['before_payment'] = [
////                '75_percent_count' => $count75,
////                '90_percent_count' => $count90,
//                'counters_count' => $countCutoff +$count75+$count90
//            ];
            $result=$countCutoff +$count75+$count90;
//            $result['after_payment'] = [
//                'payment_day_count' => 0,
//                'day_before_count' => 0
//            ];
        }
        // After payment type counters
        else {
            $paymentDay = $generatorSetting->day;

            // Convert day string to Carbon day constant
            $dayMap = [
                DaysOfWeek::Sunday => Carbon::SUNDAY,
                DaysOfWeek::Monday => Carbon::MONDAY,
                DaysOfWeek::Tuesday => Carbon::TUESDAY,
                DaysOfWeek::Wednesday => Carbon::WEDNESDAY,
                DaysOfWeek::Thursday => Carbon::THURSDAY,
                DaysOfWeek::Friday => Carbon::FRIDAY,
                DaysOfWeek::Saturday => Carbon::SATURDAY,
            ];

            $carbonDay = $dayMap[$paymentDay] ?? Carbon::MONDAY;

            // Check if today is payment day or tomorrow is payment day
            $today = Carbon::today();
            $tomorrow = Carbon::tomorrow();

            $isPaymentDay = $today->dayOfWeek === $carbonDay;
            $isDayBeforePayment = $tomorrow->dayOfWeek === $carbonDay;

            $counterCount = Counter::where('generator_id', $generatorId)->count();

//            $result['before_payment'] = [
//                '75_percent_count' => 0,
//                '90_percent_count' => 0,
//                'cut_off_count' => 0
//            ];

            if ($isPaymentDay || $isDayBeforePayment) {
                $result['after_payment'] = [
                    'counters_count' => $counterCount  ,
                ];

            }
        }

        return $result;
    }


}
