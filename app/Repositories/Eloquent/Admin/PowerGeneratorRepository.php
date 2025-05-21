<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\PowerGenerator as PowerGeneratorModel;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
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

    public function getForPlan($plan_id , array $filters): Collection
    {
        $generators=PowerGeneratorModel::planGenerators($filters)->whereRelation('subscriptions.planPrice.plan','id', $plan_id)->with(['user','subscriptions.planPrice.plan'])->get();
        return $generators;
    }
}
