<?php

namespace App\Repositories\Eloquent\Admin;
use App\Models\ElectricalBox;
use App\Repositories\interfaces\Admin\ElectricalBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ElectricalBoxRepository implements ElectricalBoxRepositoryInterface
{
    public function createBox(array $data)
    {
        return ElectricalBox::create([
            'location' => $data['location'],
            'maps' => $data['maps'],
            'number' => $data['number'],
            'capacity' => $data['capacity']
        ]);
    }

    public function getAvailableBoxes(int $areaId)
    {
        // Get the authenticated generator's ID
        $generatorId = auth()->user()->generator_id; // Adjust based on your auth structure

        return ElectricalBox::where('generator_id', $generatorId)
            // First get the count of active counters for each box
            ->leftJoin('counter__boxes', function($join) {
                $join->on('electrical_boxes.id', '=', 'counter__boxes.box_id')
                    ->whereNull('counter__boxes.removed_at');
            })
            ->select([
                'electrical_boxes.*',
                DB::raw('COUNT(counter__boxes.counter_id) as counters_count'),
                DB::raw('(electrical_boxes.capacity - COUNT(counter__boxes.counter_id)) as available_slots')
            ])
            ->groupBy('electrical_boxes.id')
            // Only show boxes with available capacity
            ->havingRaw('(electrical_boxes.capacity - counters_count) > 0')
            ->orderByDesc('available_slots')
            ->get();
    }

    public function getBoxes(int $generator_id)
    {
        return ElectricalBox::where('generator_id',$generator_id)->count();
    }
}
