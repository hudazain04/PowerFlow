<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Subscription Plan',
                    ],
                    'unit_amount' => 1000, // in cents = $10.00
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel'),
        ]);

        return response()->json([
            'url' => $session->url
        ]);
    }

    public function stripeSuccess(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session_id = $request->get('session_id');

        if (!$session_id) {
            abort(400, 'Missing session ID.');
        }

        $session = Session::retrieve($session_id);

        // You can use $session->customer_email or metadata to link to your user
        // Then create a SubscriptionRequest or mark as paid
        return response()->json([
            'message' => 'Payment successful',
            'session_id' => $session_id,
            'amount' => $session->amount_total,
        ]);
    }

    public function stripeCancel()
    {
        return response()->json(['message' => 'Payment was canceled.']);
    }
}
