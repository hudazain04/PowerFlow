<?php

namespace App\Types;

class CounterStatus
{
    public  const InCheck= 'InCheck';
    public  const Ready = "Ready";
    public const Failed = "Failed";
    public const Connect = "Connected";
        public const DisConnected="DisConnected";
    public static array $CounterTypes= [
       self::InCheck,
        self::Ready,
        self::Failed,
        self::Connect,
        self::DisConnected

    ];

}
