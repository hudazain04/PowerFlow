<?php

namespace App\Services\Admin;

use App\DTOs\AreaDTO;
use App\DTOs\AssignBoxToAreaDTO;
use App\Exceptions\GeneralException;
use App\Models\Area;
use App\Repositories\interfaces\Admin\AreaRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AreaService
{
    public function __construct(
        private AreaRepositoryInterface $repository
    ) {}

    public function createArea(AreaDTO $dto)
    {
        $generator=auth()->user()->powerGenerator->id;
        $area=$this->repository->createForGenerator(
            [     'name' => $dto->name,
                'generator_id'=>$generator,
                'neighborhood_id'=>$dto->neighborhood_id,
                'box_id' => $dto->box_id
            ]
        );
        $area=$this->repository->getRelations($area,['neighborhood']);
        return $area;
    }
    public function updateArea(array $data,int $id){


         $area=$this->repository->updateArea($data,$id);

         return $area;

    }
    public function deleteAreas(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            $generatorId = auth()->user()->powerGenerator->id;

            if (empty($ids)) {
                throw new \Exception('No areas specified for deletion');
            }


            $invalidIds = DB::table('areas')
                ->whereIn('id', $ids)
                ->where('generator_id', '!=', $generatorId)
                ->pluck('id');

            if ($invalidIds->isNotEmpty()) {
                throw new \Exception('Cannot delete areas that do not belong to your generator');
            }


            foreach ($ids as $id) {
                $this->removeAreaRelations($id);
            }

            // Delete all areas
            return $this->repository->bulkDelete($ids);
        });
    }
    private function removeAreaRelations($areaId)
    {
        DB::table('area__boxes')->where('area_id', $areaId)->delete();

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
