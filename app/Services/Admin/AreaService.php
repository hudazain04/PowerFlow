<?php

namespace App\Services\Admin;

use App\DTOs\AreaDTO;
use App\DTOs\AssignBoxToAreaDTO;
use App\Exceptions\GeneralException;
use App\Models\Area;
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
            $dto->neighborhood_id,
            ['name' => $dto->name]
        );
    }


    public function getGeneratorAreas(int $generatorId)
    {
        return $this->repository->getGeneratorAreas($generatorId);
    }
    public function getAreas(int $generator_id){
//        if(! Area::where('generator_id',$generator_id)->first()){
//            throw GeneralException::areas();
//        }
        return $this->repository->getAreas($generator_id);
    }


}
