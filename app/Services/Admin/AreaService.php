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
        $generator=auth()->user()->powerGenerator->id;
        return $this->repository->createForGenerator(
            [     'name' => $dto->name,
                'generator_id'=>$generator,
                'neighborhood_id'=>$dto->neighborhood_id,
                'box_id' => $dto->box_id
            ]
        );
    }
    public function updateArea(array $data,int $id){

         $area=$this->repository->updateArea($data,$id);

         return $area;

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
