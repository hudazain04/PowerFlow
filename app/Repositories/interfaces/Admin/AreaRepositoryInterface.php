<?php

namespace App\Repositories\interfaces\Admin;

interface AreaRepositoryInterface
{
    public function createForGenerator(int $generator_id,int $neighborhood_id, array $data);

    public function getGeneratorAreas(int $generatorId);
    public function getAreas(int $generator_id);
}
