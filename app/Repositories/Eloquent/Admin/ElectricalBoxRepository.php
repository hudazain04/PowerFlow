<?php

namespace App\Repositories\Eloquent\Admin;
use App\Models\ElectricalBox;
use App\Models\ElectricalBox as ElectricalBoxModel;
use App\Repositories\interfaces\Admin\ElectricalBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class ElectricalBoxRepository implements ElectricalBoxRepositoryInterface
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $box = ElectricalBoxModel::create($data);

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
        return ElectricalBoxModel::whereDoesntHave('areas', function($q) use ($generatorId) {
            $q->where('generator_id', '!=', $generatorId);
        })
            ->withCount(['counters' => function($q) {
                $q->whereNull('counter__boxes.removed_at');
            }])
            ->where(function($query) {
                $query->whereRaw('capacity > (
                SELECT COUNT(*)
                FROM counter__boxes
                WHERE counter__boxes.box_id = electrical_boxes.id
                AND counter__boxes.removed_at IS NULL
            )');
            })
            ->get();
    }
    public function createBox(array $data)
    {

//        return ElectricalBoxModel::create($data);
        return DB::transaction(function () use ( $data) {
            $box = ElectricalBoxModel::create($data);


            if (array_key_exists('area_id', $data)) {
                if (!is_null($data['area_id'])) {
                    $this->assignBoxToArea($data['area_id'], $box->id);
                }
            }

            return $box;
        });


    }

    public function assignBoxToArea(int $area_id, int $box_id)
    {
        return DB::table('area__boxes')->updateOrInsert(
            ['area_id' => $area_id, 'box_id' => $box_id],
            ['removed_at' => null, 'assigned_at' => now()]
        );

    }



    public function getBoxes(int $generator_id)
    {
        return ElectricalBoxModel::where('generator_id',$generator_id)->count();
    }

    public function find(int $box_id): ?ElectricalBoxModel
    {
        $box=ElectricalBoxModel::find($box_id);
        return $box;
    }

    public function get(int $generator_id)
    {


       return ElectricalBoxModel::where('generator_id', $generator_id)
            ->withCount(['counters' => function($query) {
                $query->whereNull('counter__boxes.removed_at');
            }])
            ->get();


    }

    public function updateBox($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $box = ElectricalBoxModel::findOrFail($id);

            $box->update([
                'location' => $data['location'],
                'maps' => $data['maps'],
//                'number' => $data['number'],
                'capacity' => $data['capacity'],

            ]);


            if (array_key_exists('area_id', $data)) {
                if (!is_null($data['area_id'])) {
                    $this->assignBoxToArea($data['area_id'], $box->id);
                } else {

                    $this->removeBoxFromArea($box->id);
                }
            }

            return $box;
        });
    }

    public function removeBoxFromArea(int $box_id)
    {
        return DB::table('area__boxes')
            ->where('box_id', $box_id)
            ->update(['removed_at' => now()]);
    }

    public function findById($id)
    {
        return ElectricalBoxModel::findOrFail($id);
    }

    public function delete($id)
    {
        return ElectricalBoxModel::where('id', $id)->delete();
    }

    public function bulkDelete(array $ids)
    {
        return ElectricalBoxModel::whereIn('id', $ids)->delete();
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function getBoxWithArea($id)
    {
        $box = ElectricalBoxModel::with('areas')->find($id);
//        $counters_count = $box->counters()->count();
        $box->counters_count = ElectricalBox::with('counters')->count();
        if (!$box) {
            throw new Exception(__('box.notFound'));
        }


        return $box;
    }
}
