<?php

namespace App\Observers;

use App\Models\Spending;
use App\Services\SpendingMonitorService;
use App\Types\SpendingTypes;

class SpendingObserver
{
    public function created(Spending $spending)
    {
        $counter=$spending->counter;
        if ($counter->spendingType === SpendingTypes::Before)
        {
            app(SpendingMonitorService::class)->checkConsumption($counter);
        }
    }
}
