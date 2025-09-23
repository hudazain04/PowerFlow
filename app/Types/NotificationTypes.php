<?php

namespace App\Types;

class NotificationTypes
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public const AllAdmin = "Admins";
    public const AllUser = "Users";
    public const AllEmployee = "Employees";
    public const CustomUser = "CustomUser";
    public const CustomEmployee = "CustomEmployee";
    public const All = "All";


    public static function toArray(): array
    {
        return [
            self::AllAdmin,
            self::AllUser,
            self::AllEmployee,
            self::CustomUser,
            self::CustomEmployee,
            self::All,
        ];
    }


}
