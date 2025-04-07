<?php

namespace App\Types;

class ComplaintStatusTypes
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public const Pending = "Pending";
    public const Accepted = "Accepted";
    public const InProgress = "InProgress";
    public const OnHold = "OnHold";
    public const Resolved = "Resolved";
}
