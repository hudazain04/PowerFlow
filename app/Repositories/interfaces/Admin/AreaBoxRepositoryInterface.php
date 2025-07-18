<?php

namespace App\Repositories\interfaces\Admin;

interface AreaBoxRepositoryInterface
{
 public function assignBoxToArea(int $area_id,int $box_id);
public function removeBoxFromArea(int $areaId,int $boxId);
    public function getAreaBoxes(int $areaId);
}
