<?php

namespace App\Http\Controllers\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\ApiHelper\ApiResponses;
use App\Exceptions\GeneralException;
use App\Helpers\LocationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpendingPaymentRersource;
use App\Models\Area;
use App\Models\Counter;
use App\Models\ElectricalBox;
use App\Models\Employee;
use App\Models\GeneratorSetting;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\User;
use App\Repositories\Eloquent\User\UserAppRepository;
use App\Services\User\UserAppService;
use Barryvdh\DomPDF\PDF;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class UserAppController extends Controller
{
    use ApiResponse;
    public function __construct(private UserAppService $service){}

    public function resetPassword(Request $request){
        $validate=$request->validate(['currentPassword'=>'required|string',
            'newPassword'=>'required|string']);
        $user_id=$request->user()->id;
        $password=$this->service->resetPassword($user_id,...$validate);
            return ApiResponses::success($password,__('auth.newPassword'),ApiCode::OK);
    }
    public function name(Request $request){
        $validate=$request->validate([
            'first_name'=>'required|string',
            'last_name'=>'required|string'
        ]);
        $user_id=$request->user()->id;
        $name =$this->service->name($user_id,$validate);
        return ApiResponses::success($name,'New Name',ApiCode::OK);
    }
    public function getCounters(int $id){
        $counter=$this->service->getCounters($id);
        return ApiResponses::success($counter,'user Counters',ApiCode::OK);
    }
    public function getPayments(int $id,Request $request){
        $payment=$this->service->getPayments($id,$request);
        return $this->successWithPagination(SpendingPaymentRersource::collection($payment),__('messages.success'),ApiCode::OK);
    }
    public function getCounter(int $id){
       $counter=$this->service->getCounter($id);
       return ApiResponses::success($counter,'counter detail',ApiCode::OK);
    }


    public function downloadPaymentsPdf($counterId)
    {

        $payments = Payment::where('counter_id', $counterId)->get();
        $counter = Counter::with('user')->find($counterId);
        if(!$counter){
            throw GeneralException::CounterPdf();
        }

        $totalAmount = $payments->sum('amount');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments', [
            'payments' => $payments,
            'counter' => $counter,
            'totalAmount' => $totalAmount,
            'generatedDate' => now()->format('Y-m-d H:i:s')
        ]);


        $pdf->setPaper('A4', 'portrait');


        return $pdf->download("payments_counter_{$counterId}.pdf");

    }
    public function spendingConsumption(int $counter_id){
        $consumption=$this->service->getConsumption($counter_id);
        return ApiResponses::success($consumption,'consumption rate',ApiCode::OK);
    }
    public function findNearbyGenerators(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'max_distance' => 'sometimes|numeric|min:0',
            'limit' => 'sometimes|integer|min:1|max:20'
        ]);

        $userLat = $request->latitude;
        $userLng = $request->longitude;
        $maxDistance = $request->max_distance ?? 10;
        $limit = $request->limit ?? 5;

        // Get all electrical boxes with their generators
        $boxes = ElectricalBox::with('powerGenerator')->get();

        // Debug: Check if we're getting any boxes
        if ($boxes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No electrical boxes found in the system'
            ], 404);
        }

        // Find nearest boxes
        $nearestBoxes = \App\ApiHelper\LocationHelper::findNearestBoxes(
            $userLat,
            $userLng,
            $boxes,
            $limit,
            $maxDistance
        );

        // Extract unique generators from the nearest boxes
        $generators = [];
        $seenGeneratorIds = [];

        foreach ($nearestBoxes as $box) {
            if (!in_array($box->generator_id, $seenGeneratorIds) && $box->powerGenerator) {
                $generatorData = $box->powerGenerator->toArray();
                $generatorData['distance'] = round($box->distance, 2);
                $generatorData['nearest_box_id'] = $box->id;
                $generators[] = $generatorData;
                $seenGeneratorIds[] = $box->generator_id;
            }
        }
        $result=['success' => true,
            'user_location' => [
                'lat' => $userLat,
                'lng' => $userLng
            ],
            'nearby_generators' => $generators,
            'debug_info' => [
                'total_boxes' => $boxes->count(),
                'boxes_in_range' => count($nearestBoxes),
                'generators_found' => count($generators)
            ]];
        return ApiResponses::success($result,'nearest generators',ApiCode::OK);

    }

    public function getFullData(Request $request)
    {
        $generatorId = auth()->user()->powerGenerator->id;

        // 1. Summary statistics
        $summary = [
            'total_clients' => User::whereHas('counters', function($query) use ($generatorId) {
                $query->where('generator_id', $generatorId);
            })->count(),

            'total_counters' => Counter::where('generator_id', $generatorId)->count(),

            'total_boxes' => ElectricalBox::where('generator_id', $generatorId)->count(),

            'total_employees' => Employee::where('generator_id', $generatorId)->count(),

            'total_areas' => Area::where('generator_id', $generatorId)->count(),

            'total_consumption' => Spending::whereHas('counter', function($query) use ($generatorId) {
                $query->where('generator_id', $generatorId);
            })->sum('consume'),

            'total_payments' => Payment::whereHas('counter', function($query) use ($generatorId) {
                $query->where('generator_id', $generatorId);
            })->sum('amount'),
        ];

        // 2. Get ALL clients with their relationships
        $clients = User::with(['counters' => function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        }])
            ->whereHas('counters', function($query) use ($generatorId) {
                $query->where('generator_id', $generatorId);
            })
            ->select('id', 'first_name', 'last_name', 'email', 'phone_number', 'created_at')
            ->get()
            ->map(function($client) {
                return [
                    'id' => $client->id,
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                    'email' => $client->email,
                    'phone_number' => $client->phone_number,
                    'created_at' => $client->created_at,
                    'counters' => $client->counters ? $client->counters->map(function($counter) {
                        return [
                            'id' => $counter->id,
                            'number' => $counter->number
                        ];
                    }) : []
                ];
            });

        // 3. Get ALL counters with their relationships
        $counters = Counter::with(['user', 'electricalBoxes' => function($query) {
            $query->wherePivotNull('removed_at');
        }])
            ->where('generator_id', $generatorId)
            ->get()
            ->map(function($counter) {
                return [
                    'id' => $counter->id,
                    'number' => $counter->number,
                    'QRCode' => $counter->QRCode,
                    'status' => $counter->status,
                    'current_spending' => $counter->current_spending,
                    'spendingType' => $counter->spendingType,
                    'physical_device_id' => $counter->physical_device_id,
                    'user_id' => $counter->user_id,
                    'created_at' => $counter->created_at,
                    'user' => $counter->user ? [
                        'id' => $counter->user->id,
                        'first_name' => $counter->user->first_name,
                        'last_name' => $counter->user->last_name,
                        'email' => $counter->user->email
                    ] : null,
                    'electrical_boxes' => $counter->electricalBoxes->map(function($box) {
                        return [
                            'id' => $box->id,
                            'number' => $box->number,
                            'location' => $box->location
                        ];
                    })
                ];
            });

        // 4. Get ALL boxes with their relationships
        $boxes = ElectricalBox::with(['counters' => function($query) {
            $query->wherePivotNull('removed_at');
        }])
            ->where('generator_id', $generatorId)
            ->get()
            ->map(function($box) {
                return [
                    'id' => $box->id,
                    'location' => $box->location,
                    'latitude' => $box->latitude,
                    'longitude' => $box->longitude,
                    'number' => $box->number,
                    'capacity' => $box->capacity,
                    'created_at' => $box->created_at,
                    'counters' => $box->counters->map(function($counter) {
                        return [
                            'id' => $counter->id,
                            'number' => $counter->number,
                            'status' => $counter->status
                        ];
                    })
                ];
            });

        // 5. Get ALL employees
        $employees = Employee::where('generator_id', $generatorId)
            ->select('id', 'first_name', 'last_name', 'phone_number', 'area_id', 'created_at')
            ->get();

        // 6. Get ALL areas
        $areas = Area::with(['electricalbox'])
            ->where('generator_id', $generatorId)
            ->select('id', 'name', 'neighborhood_id', 'created_at')
            ->get();

        // 7. Generator settings
        $generatorSettings = GeneratorSetting::where('generator_id', $generatorId)->first();

        // 8. Recent activities
        $recentSpendings = Spending::whereHas('counter', function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })
            ->with('counter.user')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $recentPayments = Payment::whereHas('counter', function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })
            ->with('counter.user')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        // Return Blade view with ALL data
        return view('dashboard', compact(
            'summary',
            'clients',
            'counters',
            'boxes',
            'employees',
            'areas',
            'generatorSettings',
            'recentSpendings',
            'recentPayments'
        ));
    }
    public function showDashboard()
    {
        return view('dashboard');
    }

}
