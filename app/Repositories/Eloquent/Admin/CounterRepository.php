<?php

namespace App\Repositories\Eloquent\Admin;
use App\Models\Counter;
use App\Models\ElectricalBox;
use App\Models\PowerGenerator;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CounterRepository implements CounterRepositoryInterface
{
   protected $model;
    public function __construct(Counter $counter){
        $this->model=$counter;
    }
    public function create(array $data): Counter
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Counter
    {
        return $this->model->find($id);
    }
    public function get(){
        $generator=auth()->user()->powerGenerator->id;
        $counters=Counter::where('generator_id',$generator)->get();
        return $counters;
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id',$id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function getCounters(int  $generator_id)
    {
        return DB::table('counters')
            ->join('counter__boxes', 'counters.id', '=', 'counter__boxes.counter_id')
            ->join('area__boxes', 'counter__boxes.box_id', '=', 'area__boxes.box_id')
            ->join('areas', 'area__boxes.area_id', '=', 'areas.id')
            ->where('areas.generator_id', $generator_id)
            ->count();
    }

    public function getUserCount($generator_id)
    {
        return DB::table('counters')
            ->join('counter__boxes', 'counters.id', '=', 'counter__boxes.counter_id')
            ->join('area__boxes', 'counter__boxes.box_id', '=', 'area__boxes.box_id')
            ->join('areas', 'area__boxes.area_id', '=', 'areas.id')
            ->where('areas.generator_id', $generator_id)
            ->distinct('counters.user_id')
            ->count('counters.user_id');
    }
    public function getUserCountForGenerator($generator_id)
    {
        return Counter::where(['generator_id'=>$generator_id])
            ->distinct('user_id')->count('user_id');
    }
    public function getTotalConsumption(int $generator_id)
    {
        return DB::table('spendings')
            ->join('counters', 'spendings.counter_id', '=', 'counters.id')
            ->join('counter__boxes', 'counters.id', '=', 'counter__boxes.counter_id')
            ->join('area__boxes', 'counter__boxes.box_id', '=', 'area__boxes.box_id')
            ->join('areas', 'area__boxes.area_id', '=', 'areas.id')
            ->where('areas.generator_id', $generator_id)
            ->sum('spendings.consume');
    }



    public function getWhere(array $wheres): Counter
    {
       $counters=$this->model->where($wheres)->get();
       return  $counters;
    }

}
