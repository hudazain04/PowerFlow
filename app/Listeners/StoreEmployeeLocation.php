<?php

namespace App\Listeners;

use App\Events\EmployeeLocationUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class StoreEmployeeLocation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EmployeeLocationUpdate $event): void
    {
        $employee = \App\Models\Employee::find($event->employee_id);
        if (!$employee) {
            return;
        }
        $area_id=$employee->area_id;
        Redis::geoadd(
            "geo:employees:{$area_id}",
            $event->longitude,
            $event->latitude,
            $event->employee_id,
        );
    }
}
