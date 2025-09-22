<?php

namespace App\Repositories\Eloquent\Admin;
use App\Models\Counter;
use App\Models\ElectricalBox;
use App\Models\Payment;
use App\Models\PowerGenerator;
use App\Models\Spending;
use App\Models\User;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Mockery\Expectation;
use Illuminate\Database\Eloquent\Collection;


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


    public function update(int $id, array $data): bool
    {
        return $this->model->where('id',$id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function getCounters(int  $generator_id,?array $filters=[])
    {
        $counters=Counter::where('generator_id',$generator_id)->filter($filters)->count();
        return $counters;
//        return DB::table('counters')
//            ->join('counter__boxes', 'counters.id', '=', 'counter__boxes.counter_id')
//            ->join('area__boxes', 'counter__boxes.box_id', '=', 'area__boxes.box_id')
//            ->join('areas', 'area__boxes.area_id', '=', 'areas.id')
//            ->where('areas.generator_id', $generator_id)
//            ->count();
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

    public function getRelations(Counter $counter, array $relations): Counter
    {
        $counter=$counter->load($relations);
        return $counter;
    }

    public function get($generator_id,?array $filters = [],?array $wheres=[]): Collection
    {
        $counters=Counter::where('generator_id',$generator_id)->filter($filters)->where($wheres)->get();
        return $counters;
    }

    public function latestPayment(Counter $counter): ?Payment
    {
        $latestPayment = $counter->payments()->latest()->first();
        return $latestPayment;
    }

    public function latestSpending(Counter $counter): ?Spending
    {
        $latestSpending=$counter->spendings()->latest()->first();
        return $latestSpending;
    }

    public function getUserWithCounters(int $userId, int $generatorId)
    {
        return $user=User::with(['counters' => function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId)
                ->with(['electricalBoxes' => function($q) {
                    $q->wherePivotNull('removed_at');
                }]);




        }])
            ->where('id', $userId)
            ->firstOrFail();
    }

    public function getUsersByGeneratorWithSearch(int $generatorId, string $search = '', string $searchField = 'all')
    {
        $query = User::whereHas('counters', function($query) use ($generatorId) {
            $query->where('generator_id', $generatorId);
        })
            ->withCount(['counters' => function($query) use ($generatorId) {
                $query->where('generator_id', $generatorId);
            }]);


        if (!empty($search)) {
            switch ($searchField) {
                case 'first_name':
                    $query->where('first_name', 'like', "%{$search}%");
                    break;
                case 'last_name':
                    $query->where('last_name', 'like', "%{$search}%");
                    break;
                case 'phone_number':
                    $query->where('phone_number', 'like', "%{$search}%");
                    break;
                case 'email':
                    $query->where('email', 'like', "%{$search}%");
                    break;
                case 'all':
                default:
                    $query->where(function($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                    break;
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
}
