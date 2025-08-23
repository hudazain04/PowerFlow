<?php

namespace App\Payment\Methods;

use App\Payment\Visitors\PaymentVisitor;

class CashPayment implements PaymentMethod
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function accept(PaymentVisitor $visitor)
    {
        return $visitor->visitCash($this);
    }
}
