<?php

namespace App\ApiHelper;

use App\Jobs\TranslateDataJob;

trait Translatable
{
    public static function bootTranslatable()
    {
        static::created(function ($model) {
            $model->dispatchTranslationJob();
        });

        static::updated(function ($model) {
            $model->dispatchTranslationJob();
        });
    }

    protected function dispatchTranslationJob()
    {
//            TranslateDataJob::dispatch($this, 'en');
    }
}
