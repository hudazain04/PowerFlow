<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\Models\Visitor as VisitorModel;
use App\Repositories\interfaces\SuperAdmin\VisitorRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VisitorRepository implements VisitorRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function count(): int
    {
        $visitorsCount=VisitorModel::count();
        return $visitorsCount;
    }

    public function dailyAvg(): int
    {

        $visits = DB::table('visitors')
            ->select(DB::raw('DATE(visited_at) as visit_date'), DB::raw('COUNT(*) as daily_visits'))
            ->groupBy('visit_date')
            ->get();

        $totalDays = $visits->count();
        $totalVisits = $visits->sum('daily_visits');

        return $totalDays > 0 ? $totalVisits / $totalDays : 0;
    }
}
