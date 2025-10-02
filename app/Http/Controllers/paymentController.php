<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\StripePayRequest;
use App\Payment\Methods\CashPayment;
use App\Payment\Methods\StripePayment;
use App\Payment\Visitors\PaymentProcessor;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class paymentController extends Controller
{
    public function __construct(
       protected PaymentService $paymentService,
    )
    {

    }
//    public function createCheckoutSession(Request $request)
//    {
//        Stripe::setApiKey(config('services.stripe.secret'));
//
//        $session = Session::create([
//            'payment_method_types' => ['card'],
//            'mode' => 'payment',
//            'line_items' => [[
//                'price_data' => [
//                    'currency' => 'usd',
//                    'product_data' => [
//                        'name' => 'Subscription Plan',
//                    ],
//                    'unit_amount' => 1000,
//                ],
//                'quantity' => 1,
//            ]],
//            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
//            'cancel_url' => route('stripe.cancel'),
//        ]);
//
//        return response()->json([
//            'url' => $session->url
//        ]);
//    }
//
//    public function stripeSuccess(Request $request)
//    {
//        Stripe::setApiKey(config('services.stripe.secret'));
//
//        $session_id = $request->get('session_id');
//
//        if (!$session_id) {
//            abort(400, 'Missing session ID.');
//        }
//
//        $session = Session::retrieve($session_id);
//
//        return response()->json([
//            'message' => 'Payment successful',
//            'session_id' => $session_id,
//            'amount' => $session->amount_total,
//        ]);
//    }
//
//    public function stripeCancel()
//    {
//        return response()->json(['message' => 'Payment was canceled.']);
//    }

    public function createStripeCheckout(Request  $request, $subscriptionRequest_id)
    {
        $payment=$this->paymentService->createStripeCheckout($subscriptionRequest_id);
        return  $payment;
    }

    public function stripeSuccess(Request $request)
    {
       $this->paymentService->stripeSuccess($request);
       return redirect(env("FRONTEND_HOST").'?shouldRedirect=true&paid=true');
    }

    public function stripeCancel(Request  $request)
    {
        $this->paymentService->stripeCancel($request);
        return redirect(env("FRONTEND_HOST").'?shouldRedirect=true&paid=false');
    }

    public function handleCashPayment($subscriptionRequest_id)
    {
       return $this->paymentService->handleCashPayment($subscriptionRequest_id);
    }
}
