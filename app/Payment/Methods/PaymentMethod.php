<?php

namespace App\Payment\Methods;

use App\Payment\Visitors\PaymentVisitor;

interface PaymentMethod
{
    public function accept(PaymentVisitor $visitor);
}
