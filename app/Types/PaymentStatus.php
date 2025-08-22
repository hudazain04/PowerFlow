<?php

namespace App\Types;

class PaymentStatus
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public const Paid='Paid';
    public const Pending='Pending';
    public const Cancelled='Cancelled';
}
