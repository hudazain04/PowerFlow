<?php

namespace App\Http\Controllers\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\ApiHelper\ApiResponses;
use App\Exceptions\GeneralException;
use App\Helpers\LocationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpendingPaymentRersource;
use App\Models\Counter;
use App\Models\ElectricalBox;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\Eloquent\User\UserAppRepository;
use App\Services\User\UserAppService;
use Barryvdh\DomPDF\PDF;
use \Illuminate\Http\Request;
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

}
