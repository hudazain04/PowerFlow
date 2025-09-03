<?php

namespace App\ApiHelper;

use App\Services\FeatureGate;

trait HasFeatureLimit
{
    public static function bootHasFeatureLimit()
    {
        static::creating(function ($model) {
            $generator_id = $model->powerGenerator->id ?? null;
            $featureKey = $model->featureKey ?? null;

            if ($generator_id && $featureKey) {
                $gate = new FeatureGate();

                if (!$gate->check($generator_id, $featureKey)) {
                    throw new \Exception(__('messages.feature.limitReached'));
                }
            }
        });
    }
}
