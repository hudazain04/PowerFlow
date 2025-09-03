<?php

namespace App\Repositories\interfaces\Admin;

interface AreaRepositoryInterface
{
    public function createForGenerator( array $data);
    public function updateArea(array $data,int $id);
    public function getGeneratorAreas(int $generatorId);
    public function getAreas(int $generator_id);
}
