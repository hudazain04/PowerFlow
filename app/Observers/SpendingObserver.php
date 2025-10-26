<?php

namespace App\Observers;

use App\Models\Action;
use App\Models\Spending;
use App\Services\SpendingMonitorService;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use App\Types\SpendingTypes;

class SpendingObserver
{
    public function created(Spending $spending)
    {
        $counter=$spending->counter;
        $counter->update(['current_spending'=>$spending->consume/1000]);
        $counter->save();
        if ($counter->spendingType === SpendingTypes::Before)
        {
            app(SpendingMonitorService::class)->checkSpending($counter);
        }

       app(SpendingMonitorService::class)->checkOverConsume($counter);
    }
}
