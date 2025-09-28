<?php

namespace App\Types;

class CounterStatus
{
//    public  const InCheck= 'InCheck';
    public  const Ready = "Ready";

    public const Connect = "Connected";
        public const DisConnected="DisConnected";
    public static array $CounterTypes= [

        self::Ready,

        self::Connect,
        self::DisConnected

    ];

}
