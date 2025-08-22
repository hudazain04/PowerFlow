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
}
