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
        $counter->update(['current_spending'=>$spending->consume]);
        $counter->save();
        if ($counter->spendingType === SpendingTypes::Before)
        {
            app(SpendingMonitorService::class)->checkSpending($counter);
        }
    }
}
