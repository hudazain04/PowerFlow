<?php

namespace App\Repositories\Eloquent\Admin;
use App\Models\ElectricalBox;
use App\Repositories\interfaces\Admin\ElectricalBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ElectricalBoxRepository implements ElectricalBoxRepositoryInterface
{
    public function createBox(array $data)
    {

        return DB::transaction(function ()use($data){
            $generator=auth()->user()->powerGenerator->id;
            $box= ElectricalBox::create([
                'location' => $data['location'],
                'maps' => $data['maps'],
                'number' => $data['number'],
                'capacity' => $data['capacity'],
                'generator_id' => $generator

            ]);
            if (array_key_exists('area_id', $data) && !is_null($data['area_id'])) {
                $this->assignBoxToArea($data['area_id'], $box->id);
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
        return ElectricalBox::where('generator_id',$generator_id)->count();
    }

    public function get(int $generator_id)
    {
        return ElectricalBox::where('generator_id',$generator_id)->get();
    }

    public function updateBox($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $box = ElectricalBox::findOrFail($id);

            $box->update([
                'location' => $data['location'],
                'maps' => $data['maps'],
                'number' => $data['number'],
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
        return ElectricalBox::findOrFail($id);
    }

    public function delete($id)
    {
        return ElectricalBox::where('id', $id)->delete();
    }

    public function bulkDelete(array $ids)
    {
        return ElectricalBox::whereIn('id', $ids)->delete();
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }
}
