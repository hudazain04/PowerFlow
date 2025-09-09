<?php
// app/Helpers/LocationHelper.php

namespace App\ApiHelper;

class LocationHelper
{
    public static function calculateDistance($lat1, $lng1, $lat2, $lng2, $unit = 'km')
    {
        $theta = $lng1 - $lng2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        if ($unit == 'km') {
            return ($miles * 1.609344);
        } else {
            return $miles;
        }
    }

    public static function findNearestBoxes($userLat, $userLng, $boxes, $limit = 5, $maxDistance = 10)
    {
        $boxesWithDistance = [];

        foreach ($boxes as $box) {
            // Use the correct field names from your model
            $distance = self::calculateDistance($userLat, $userLng, $box->latitude, $box->longitude);

            if ($distance <= $maxDistance) {
                $box->distance = $distance;
                $boxesWithDistance[] = $box;
            }
        }

        // Sort by distance
        usort($boxesWithDistance, function($a, $b) {
            return $a->distance <=> $b->distance;
        });

        return array_slice($boxesWithDistance, 0, $limit);
    }
}
