<?php

namespace App\Repositories\interfaces\SuperAdmin;

use Illuminate\Support\Collection;

interface SubscriptionRequestRepositoryInterface
{
    public function count() : int;

    public function getLastFive() : Collection;

    public function getAllWithPlan() : Collection;


}
