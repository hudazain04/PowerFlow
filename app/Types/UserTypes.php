<?php

namespace App\Types;

class UserTypes
{
    /**
     * Create a new class instance.
     */

    public const EMPLOYEE = 'employee';
    public const USER = 'user';
    public const ADMIN = 'admin';
    public const SUPER_ADMIN = 'super admin';

    public static array $statuses = [
        self::EMPLOYEE,
        self::USER,
        self::ADMIN,
        self::SUPER_ADMIN
    ];
}
