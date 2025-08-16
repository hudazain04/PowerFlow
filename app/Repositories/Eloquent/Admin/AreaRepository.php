<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Area;
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

    public function createForGenerator(int $generator_id,int $neighborhood_id, array $data)
    {
        $generator = PowerGenerator::findOrFail($generator_id);
        $neighborhood = Neighborhood::findOrFail($neighborhood_id);

        return $this->model->create([
            'name' => $data['name'],
            'neighborhood_id'=>$neighborhood->id,
            'generator_id'=>$generator->id,
        ]);
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
}
