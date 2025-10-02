<?php

namespace App\Types;

class SubscriptionTypes
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public const Renew = "renew";
    public const Upgrade="upgrade";
    public const Cancel = "cancel";
    public const NewPlan = "new";
}
