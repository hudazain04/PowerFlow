<?php

namespace App\Types;

class CounterStatus
{
    public  const InCheck= 'InCheck';
    public  const Ready = "Ready";
    public const Failed = "Failed";
    public static array $CounterTypes= [
       self::InCheck,
        self::Ready,
        self::Failed
    ];

}
