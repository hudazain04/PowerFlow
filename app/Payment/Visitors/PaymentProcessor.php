<?php

namespace App\Payment\Visitors;

use App\Payment\Methods\CashPayment;
use App\Payment\Methods\StripePayment;
use App\Types\PaymentStatus;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentProcessor implements PaymentVisitor
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function visitStripe(StripePayment $payment)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if (!$payment->sessionId) {
            // ✅ Create new Checkout Session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $payment->name,
                        ],
                        'unit_amount' => $payment->amount,
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => $payment->success . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $payment->cancel . '?session_id={CHECKOUT_SESSION_ID}',
            ]);

            return [
                'status' => 'created',
                'url' => $session->url,
                'session_id' => $session->id,
            ];
        } else {
            $session = Session::retrieve($payment->sessionId);
            if ($session->payment_status === 'paid') {
                return [
                    'status' => 'success',
                    'amount' => $session->amount_total,
                    'session_id' => $payment->sessionId,
                ];
            }

            return ['status' => 'failed','session_id'=>$payment->sessionId];
        }
    }
    public function visitCash(CashPayment $cashPayment)
    {
        return [
            'status' => PaymentStatus::Paid,
        ];
    }
}
