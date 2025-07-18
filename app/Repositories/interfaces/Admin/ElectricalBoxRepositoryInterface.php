<?php

namespace App\Repositories\interfaces\Admin;

interface ElectricalBoxRepositoryInterface
{
    public function createBox(array $data);
    public function getAvailableBoxes(int $areaId);
}
