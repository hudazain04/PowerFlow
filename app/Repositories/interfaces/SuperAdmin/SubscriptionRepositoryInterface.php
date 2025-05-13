<?php

namespace App\Repositories\interfaces\SuperAdmin;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function getAllWithPlan() : Collection;

    public function getSubscriptionsWithMonths(int $year) : Collection;

}
