<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Area;
use App\Models\ElectricalBox;
use App\Models\Neighborhood;
use App\Models\PowerGenerator;
use App\Repositories\interfaces\Admin\AreaRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AreaRepository implements AreaRepositoryInterface
{
    private $model;
    public function __construct(Area $model)
    {
        $this->model=$model;
    }

    public function createForGenerator(array $data)
    {

         $area=$this->model->create([
            'name' => $data['name'],
            'neighborhood_id'=>$data['neighborhood_id'],
            'generator_id'=>$data['generator_id'],
        ]);

         if (array_key_exists('box_id',$data)){
             $box_ids = is_array($data['box_id']) ? $data['box_id'] : [$data['box_id']];
             foreach ($box_ids as $box_id) {
                 $this->assignBoxToArea($area->id, $box_id);
             }
         }
         return $area;

    }

    public function updateArea(array $data, int $id)
    {
        $area=Area::findOrFail($id);
        $generator=auth()->user()->powerGenerator->id;
        $area->update([
            'name' => $data['name'],
            'neighborhood_id'=>$data['neighborhood_id'],
            'generator_id'=>$generator,
        ]);

        if (array_key_exists('box_id',$data)){
            $box_ids = is_array($data['box_id']) ? $data['box_id'] : [$data['box_id']];
            DB::table('area__boxes')
                ->where('area_id', $id)->delete();
            foreach ($box_ids as $box_id) {

                $this->assignBoxToArea($area->id, $box_id);
            }
        }
        return $area;

    }

    public function bulkDelete(array $ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function assignBoxToArea(int $area_id, int $box_id)
    {
        $boxExists = ElectricalBox::where('id', $box_id)->exists();

        if (!$boxExists) {
            throw new \Exception("Electrical box with ID {$box_id} does not exist");
        }


        return DB::table('area__boxes')->updateOrInsert(
            ['area_id' => $area_id, 'box_id' => $box_id],
            ['removed_at' => null, 'assigned_at' => now()]
        );
    }



    public function getGeneratorAreas(int $generator_id)
    {
        return Area::where('generator_id', $generator_id)
            ->get();
    }

//    public function getAreaBoxes(int $area_id)
//    {
//        return DB::table('area__boxes')
//            ->where('area_id', $area_id)
//            ->whereNull('removed_at')
//            ->join('electrical_boxes', 'electrical_boxes.id', '=', 'area__boxes.box_id')
//            ->select('electrical_boxes.*')
//            ->get();
//    }
    public function getAreas(int $generator_id)
    {
        return Area::where('generator_id',$generator_id)->count();
    }


    public function getRelations(Area $area, array $relations = []): Area
    {
        $area=$area->load($relations);
        return $area;
    }

    public function find(int $area_id): Area
    {
        $area=Area::find($area_id);
        return $area;
    }
}
