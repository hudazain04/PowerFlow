<?php

namespace App\Repositories\Eloquent\Admin;

use App\Repositories\interfaces\Admin\AreaBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AreaBoxRepository implements AreaBoxRepositoryInterface
{

    public function assignBoxToArea(int $area_id, int $box_id)
    {
        return DB::table('area__boxes')->updateOrInsert(
            ['area_id' => $area_id, 'box_id' => $box_id],
            ['removed_at' => null, 'assigned_at' => now()]
        );
    }

    public function removeBoxFromArea(int $areaId, int $boxId)
    {
        return DB::table('area__boxes')
            ->where('area_id', $areaId)
            ->where('box_id', $boxId)
            ->whereNull('removed_at')
            ->update(['removed_at' => now()]);
    }

    public function getAreaBoxes(int $areaId)
    {
        return DB::table('area__boxes')
            ->where('area_id', $areaId)
            ->whereNull('removed_at')
            ->join('electrical_boxes', 'electrical_boxes.id', '=', 'area__boxes.box_id')
            ->select('electrical_boxes.*')
            ->get();
    }
}
