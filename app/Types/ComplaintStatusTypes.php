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
    public const Assigned="Assigned";
    public const Accepted = "Accepted";
    public const Rejected = "Rejected";
    public const InProgress = "InProgress";
    public const OnHold = "OnHold";
    public const Resolved = "Resolved";
}
