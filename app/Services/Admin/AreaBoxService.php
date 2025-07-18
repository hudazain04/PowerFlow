<?php

namespace App\Services\Admin;

use App\Repositories\Eloquent\Admin\AreaBoxRepository;
use App\Repositories\Eloquent\Admin\ElectricalBoxRepository;
use Illuminate\Support\Facades\DB;

class AreaBoxService
{
    public function __construct(
        private AreaBoxRepository $areaBoxRepo,
        private ElectricalBoxRepository $boxRepo
    ) {}

    public function assignBox( int $area_id,int $box_id)
    {

        $existing = DB::table('area__boxes')
            ->where('box_id', $box_id)
            ->whereNull('removed_at')
            ->exists();

        if ($existing) {
            throw new \Exception('Box is already assigned to another area');
        }

        return $this->areaBoxRepo->assignBoxToArea($area_id, $box_id);
    }
    public function removeBoxFromArea(int $area_id, int $box_id)
    {
        // Verify the box is actually assigned to this area
        $assigned = DB::table('area__boxes')
            ->where('area_id', $area_id)
            ->where('box_id', $box_id)
            ->whereNull('removed_at')
            ->exists();

        if (!$assigned) {
            throw new \Exception('Box is not currently assigned to this area');
        }

        return $this->areaBoxRepo->removeBoxFromArea($area_id, $box_id);
    }

    public function getAvailableBoxes(int $areaId)
    {
        return $this->boxRepo->getAvailableBoxes($areaId);
    }

    public function getAreaBoxes(int $areaId)
    {
        return $this->areaBoxRepo->getAreaBoxes($areaId);
    }

}
