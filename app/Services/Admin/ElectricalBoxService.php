<?php

namespace App\Services\Admin;

use App\DTOs\AssignCounterToBoxDTO;
use App\DTOs\ElectricalBoxDTO;
use App\Exceptions\GeneralException;
use App\Models\ElectricalBox;
use App\Repositories\Eloquent\Admin\ElectricalBoxRepository;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\Admin\ElectricalBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ElectricalBoxService
{

    public function __construct(
        private ElectricalBoxRepository $boxRepo
    ) {}

    public function createBox(array $data)
    {

        if (ElectricalBox::where('number', $data['number'])->exists()) {
            throw new \Exception('Box number already exists');
        }

        return $this->boxRepo->createBox($data);
    }
    public function getBoxes(int $generator_id){
//        if(! ElectricalBox::where('generator_id',$generator_id)->first()){
//            throw GeneralException::boxes();
//        }
        return $this->boxRepo->getBoxes($generator_id);
    }
    public function get(int $generator_id){
        return $this->boxRepo->get($generator_id);
    }

    public function updateBox($id, array $data)
    {
        // Check if number already exists (excluding current box)
        if (ElectricalBox::where('number', $data['number'])->where('id', '!=', $id)->exists()) {
            throw new \Exception('Box number already exists');
        }

        return $this->boxRepo->updateBox($id, $data);
    }
    public function deleteBoxes(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            $generatorId = auth()->user()->powerGenerator->id;

            if (empty($ids)) {
                throw new \Exception('No boxes specified for deletion');
            }

            // Verify all boxes belong to this generator
            $invalidIds = DB::table('electrical_boxes')
                ->whereIn('id', $ids)
                ->where('generator_id', '!=', $generatorId)
                ->pluck('id');

            if ($invalidIds->isNotEmpty()) {
                throw new \Exception('Cannot delete boxes that do not belong to your generator');
            }

            // Remove relations for all boxes
            foreach ($ids as $id) {
                $this->removeBoxRelations($id);
            }

            // Delete all boxes
            return $this->boxRepo->bulkDelete($ids);
        });
    }


    public function bulkDeleteBoxes(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            $generatorId = auth()->user()->powerGenerator->id;


            $invalidIds = DB::table('electrical_boxes')
                ->whereIn('id', $ids)
                ->where('generator_id', '!=', $generatorId)
                ->pluck('id');

            if ($invalidIds->isNotEmpty()) {
                throw new \Exception('Cannot delete boxes that do not belong to your generator');
            }


            foreach ($ids as $id) {
                $this->removeBoxRelations($id);
            }


            return $this->boxRepo->bulkDelete($ids);
        });
    }
    private function removeBoxRelations($boxId)
    {

         DB::table('area__boxes')->where('box_id', $boxId)->delete();
         DB::table('counter__boxes')->where('box_id', $boxId)->delete();
    }
}
