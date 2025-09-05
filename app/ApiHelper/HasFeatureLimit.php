<?php

namespace App\ApiHelper;

use App\Exceptions\ErrorException;
use App\Services\FeatureGate;

trait HasFeatureLimit
{
    public static function bootHasFeatureLimit()
    {
        static::creating(function ($model) {
            $generator_id = $model->powerGenerator->id ?? null;
            $featureKey = $model->featureKey ?? null;
            if ($generator_id && $featureKey) {
                $gate = app(FeatureGate::class);
                if (!$gate->check($generator_id, $featureKey)) {
//                    throw new ErrorException(__('feature.limitReached'),ApiCode::FORBIDDEN);
                }
            }
        });
    }
}
