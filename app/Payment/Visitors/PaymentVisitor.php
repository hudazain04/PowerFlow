<?php

namespace App\Payment\Visitors;

use App\Payment\Methods\CashPayment;
use App\Payment\Methods\StripePayment;

interface PaymentVisitor
{
    public function visitStripe(StripePayment $stripePayment);
    public function visitCash(CashPayment $cashPayment);
}
