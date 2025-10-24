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
    public const Pending = "pending";
    public const Assigned="assigned";
    public const Accepted = "accepted";
    public const Rejected = "rejected";
    public const InProgress = "inProgress";
    public const OnHold = "onHold";
    public const Resolved = "resolved";
    public static array $statuses = [
        self::Accepted,
        self::Resolved,
        self::Pending

    ];
}
