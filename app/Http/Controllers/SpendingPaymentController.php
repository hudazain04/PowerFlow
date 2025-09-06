<?php

namespace App\Http\Controllers;

use App\DTOs\SpendingPayDTO;
use App\Http\Requests\Payment\SpendingPayRequest;
use App\Services\SpendingPaymentService;
use Illuminate\Http\Request;

class SpendingPaymentController extends Controller
{
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
}
