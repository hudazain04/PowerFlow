<?php

namespace App\Repositories\interfaces\SuperAdmin;

use App\Types\SubscriptionTypes;
use Illuminate\Support\Collection;
use App\Models\SubscriptionRequest as SubscriptionRequestModel;

interface SubscriptionRequestRepositoryInterface
{
    public function count() : int;

    public function getLastFive() : Collection;

    public function getAllWithPlan() : Collection;

    public function getRequestsCountForPlan(int $plan_id) : int;

    public function getRequestsForPlan(int $plan_id ,  ?string $type) : Collection;

}
