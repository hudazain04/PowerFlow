<?php

namespace App\Services\Admin;

use App\DTOs\AreaDTO;
use App\DTOs\AssignBoxToAreaDTO;
use App\Repositories\interfaces\Admin\AreaRepositoryInterface;

class AreaService
{
    public function __construct(
        private AreaRepositoryInterface $repository
    ) {}

    public function createArea(AreaDTO $dto)
    {
        return $this->repository->createForGenerator(
            $dto->generator_id,
            ['name' => $dto->name]
        );
    }

    public function assignBoxToArea(AssignBoxToAreaDTO $dto)
    {
        return $this->repository->assignBox($dto->area_id, $dto->box_id);
    }

    public function getGeneratorAreas(int $generatorId)
    {
        return $this->repository->getGeneratorAreas($generatorId);
    }

    public function getAreaBoxes(int $areaId)
    {
        return $this->repository->getAreaBoxes($areaId);
    }
}
