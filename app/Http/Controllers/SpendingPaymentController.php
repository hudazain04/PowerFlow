<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\SpendingPayDTO;
use App\Http\Requests\Payment\SpendingPayRequest;
use App\Http\Resources\SpendingPaymentRersource;
use App\Services\SpendingPaymentService;
use Illuminate\Http\Request;

class SpendingPaymentController extends Controller
{

    use ApiResponse;
    public function __construct(
        protected SpendingPaymentService $spendingPaymentService,
    )
    {
    }
    public function createStripeCheckout(SpendingPayRequest  $request,$counter_id)
    {
        $dto=SpendingPayDTO::fromRequest($request);
        $payment=$this->spendingPaymentService->createStripeCheckout($dto,$counter_id);
        return  $payment;

    }

    public function stripeSuccess(Request $request)
    {
        return $this->spendingPaymentService->stripeSuccess($request);
    }

    public function stripeCancel(Request  $request)
    {
        return  $this->spendingPaymentService->stripeCancel($request);
    }

    public function handleCashPayment(SpendingPayRequest  $request,$counter_id)
    {
        $dto=SpendingPayDTO::fromRequest($request);
        return $this->spendingPaymentService->handleCashPayment($dto,$counter_id);
    }

    public function getSpendingPayments(Request $request)
    {
        $generator_id=$request->user()->powerGenerator->id;
        $payments=$this->spendingPaymentService->getSpendingPayments($generator_id,$request);
        $totalAmount = $payments->sum('amount');
        $payments = $payments->paginate(20);

        return $this->successWithPagination(SpendingPaymentRersource::collection($payments),
            __('messages.success'),
            ApiCode::OK,
            ['total_amount' => $totalAmount]
        );
    }
}
