<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\ElectricalBox as ElectricalBoxModel;

interface ElectricalBoxRepositoryInterface
{
    public function createBox(array $data);
    public function getAvailableBoxes(int $areaId);
    public function getBoxes(int $generator_id);
    public function find(int $box_id) : ?ElectricalBoxModel;
}
