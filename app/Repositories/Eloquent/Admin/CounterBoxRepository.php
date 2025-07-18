<?php

namespace App\Repositories\Eloquent\Admin;

use App\Repositories\interfaces\Admin\CounterBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CounterBoxRepository implements CounterBoxRepositoryInterface
{
    public function assignCounterToBox(int $counterId, int $boxId)
    {
        return DB::table('counter__boxes')->updateOrInsert(
            ['counter_id' => $counterId, 'box_id' => $boxId],
            ['removed_at' => null, 'installed_at' => now()]
        );
    }

    public function removeCounterFromBox(int $counterId, int $boxId)
    {
        return DB::table('counter__boxes')
            ->where('counter_id', $counterId)
            ->where('box_id', $boxId)
//            ->whereNull('removed_at') do not forget if you make exception you can return this line
            ->update(['removed_at' => now()]);
    }

    public function getCurrentBox(int $counterId)
    {
        return DB::table('counter__boxes')
            ->where('counter_id', $counterId)
            ->whereNull('removed_at')
            ->join('electrical_boxes', 'electrical_boxes.id', '=', 'counter__boxes.box_id')
            ->select('electrical_boxes.*')
            ->first();
    }

    public function getBoxCounters(int $boxId)
    {
        return DB::table('counter__boxes')
            ->where('box_id', $boxId)
            ->whereNull('removed_at')
            ->join('counters', 'counters.id', '=', 'counter__boxes.counter_id')
            ->select('counters.*')
            ->get();
    }


}
