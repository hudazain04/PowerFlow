<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\PowerGenerator as PowerGeneratorModel;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PowerGeneratorRepository implements PowerGeneratorRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function count(): int
    {
        return PowerGeneratorModel::count();
    }
    public function find(int $id){
        return PowerGeneratorModel::where('id',$id)->first();
    }
    public function create(array $data){
        return PowerGeneratorModel::create($data);
    }



    public function getForPlan($plan_id , array $filters): Collection
    {
        $generators=PowerGeneratorModel::planGenerators($filters)->whereRelation('subscriptions.planPrice.plan','id', $plan_id)->with(['user','subscriptions.planPrice.plan'])->get();
        return $generators;
    }

    public function getAll(array $filters): LengthAwarePaginator
    {
        $generators=PowerGeneratorModel::filter($filters)->with(['user','subscriptions'])->paginate(10);
        return $generators;
    }

    public function getRelationCount(PowerGeneratorModel $powerGenerator, string $relation): int
    {
        $powerGenerator=$powerGenerator->loadCount($relation);
        return $powerGenerator->getAttribute("{$relation}_count");
    }

    public function update(PowerGeneratorModel $powerGenerator, array $data): PowerGeneratorModel
    {
        $powerGenerator->update($data);
        $powerGenerator->save();
        return $powerGenerator;
    }
}
