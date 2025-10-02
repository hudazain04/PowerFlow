<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\Area;

interface AreaRepositoryInterface
{
    public function createForGenerator( array $data);
    public function updateArea(array $data,Area $area);
    public function getGeneratorAreas(int $generatorId);
    public function getAreas(int $generator_id);

    public function getRelations(Area $area, array $relations=[]) :  Area;

    public function find(int $area_id) : Area;
}
