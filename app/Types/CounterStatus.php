<?php

namespace App\Types;

class CounterStatus
{
//    public  const InCheck= 'InCheck';
    public  const Ready = "ready";

    public const Connect = "connected";
        public const DisConnected="disConnected";
    public static array $CounterTypes= [

        self::Ready,

        self::Connect,
        self::DisConnected

    ];

}
