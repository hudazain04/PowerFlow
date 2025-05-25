<?php

namespace App\ApiHelper;

trait HelperMethods
{
    function calculateTotalPrice(int $monthlyPrice, int $discount, int $period): float
    {
        $discountRate = $discount / 100;
        $total = 0;

        for ($i = 0; $i < $period; $i++) {
            $discountedPrice = $monthlyPrice * pow(1 - $discountRate, $i);
            $total += $discountedPrice;
        }

        return round($total, 2);
    }
}
