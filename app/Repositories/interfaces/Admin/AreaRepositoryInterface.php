<?php

namespace App\Repositories\interfaces\Admin;

interface AreaRepositoryInterface
{
    public function createForGenerator(int $generator_id, array $data);
    public function assignBox(int $areaId, int $boxId);
    public function getGeneratorAreas(int $generatorId);
}
