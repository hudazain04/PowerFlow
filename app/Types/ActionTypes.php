<?php

namespace App\Types;

class ActionTypes
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public const Cut="cut";
    public const Connect="connect";
    public const OverConsume="overConsume";
    public const Payment="payment";

    public const PRIORITIES = [
        self::Cut         => 2,
        self::OverConsume => 1,
        self::Connect     => 3,
        self::Payment     => 4,
    ];

    public static function getPriority(string $type): int
    {
        return self::PRIORITIES[$type] ?? 0;
    }
    public static function all(): array
    {
        return [
            self::Cut,
            self::Connect,
            self::OverConsume,
            self::Payment,
        ];
    }
}
