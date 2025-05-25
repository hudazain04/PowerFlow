<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Area;
use App\Repositories\interfaces\Admin\AreaRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AreaRepository implements AreaRepositoryInterface
{
    public function createForGenerator(int $generator_id, array $data)
    {
        return Area::create($data);
    }

    public function assignBox(int $area_id, int $box_id)
    {
        return DB::table('area__boxes')->updateOrInsert(
            ['area_id' => $area_id, 'box_id' => $box_id],
            ['removed_at' => null]
        );
    }

    public function getGeneratorAreas(int $generator_id)
    {
        return Area::where('generator_id', $generator_id)
            ->withCount('boxes')
            ->get();
    }

    public function getAreaBoxes(int $area_id)
    {
        return DB::table('area__boxes')
            ->where('area_id', $area_id)
            ->whereNull('removed_at')
            ->join('electrical_boxes', 'electrical_boxes.id', '=', 'area__boxes.box_id')
            ->select('electrical_boxes.*')
            ->get();
    }
}
