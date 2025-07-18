<?php

namespace App\Repositories\interfaces\SuperAdmin;
use App\Types\SubscriptionExpirationTypes;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function getAllWithPlan() : Collection;

    public function getSubscriptionsWithMonths(int $year) : Collection;

    public function getSubscriptionsForPlan(int $plan_id, ?string $type) : Collection;

}
