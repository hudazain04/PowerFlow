<?php

namespace App\Services\Admin;


use App\Exceptions\GeneralException;
use App\Models\Area;
use App\Models\Counter;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use Illuminate\Http\Request;

class CounterService
{
    public function __construct(
        private CounterRepositoryInterface $repository
    ) {}

   public function getCounters(int $generator_id){

       return $this->repository->getCounters($generator_id);
   }
   public function getUsers(int $generator_id){
        return $this->repository->getUserCount($generator_id);
   }
   public function consumption(int $generator_id){
        return $this->repository->getTotalConsumption($generator_id);

   }
   public function get(Request $request){
       $generator=auth()->user()->powerGenerator->id;
       return $this->repository->get($generator,[ 'status' => $request->query('status')]);
   }
}
