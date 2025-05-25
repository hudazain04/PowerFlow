<?php

namespace App\Repositories\Eloquent\Admin;
use App\Models\ElectricalBox;
use App\Repositories\interfaces\Admin\ElectricalBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ElectricalBoxRepository implements ElectricalBoxRepositoryInterface
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $box = ElectricalBox::create($data);

            return $box;
        });
    }

    public function assignCounter(int $boxId, int $counter_id)
    {
        // Close any existing assignment
        DB::table('counter__boxes')
            ->where('counter_id', $counter_id)
            ->whereNull('removed_at')
            ->update(['removed_at' => now()]);

        // Create new assignment
        return DB::table('counter__boxes')->insert([
            'counter_id' => $counter_id,
            'box_id' => $boxId,
            'installed_at' => now()
        ]);
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

    public function getAvailableBoxes(int $generatorId)
    {
        return ElectricalBox::whereDoesntHave('areas', function($q) use ($generatorId) {
            $q->where('generator_id', '!=', $generatorId);
        })
            ->withCount(['counters' => function($q) {
                $q->whereNull('counter__boxes.removed_at');
            }])
            ->havingRaw('counters_count < max_capacity')
            ->get();
    }
}
