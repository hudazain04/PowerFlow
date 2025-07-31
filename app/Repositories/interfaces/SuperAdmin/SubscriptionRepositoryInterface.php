<?php

namespace App\Repositories\interfaces\SuperAdmin;
use App\Types\SubscriptionExpirationTypes;
use Illuminate\Support\Collection;
use App\Models\Subscription as SubscriptionModel;


interface SubscriptionRepositoryInterface
{
    public function getAllWithPlan() : Collection;

    public function getSubscriptionsWithMonths(int $year) : Collection;

    public function getSubscriptionsForPlan(int $plan_id, ?string $type) : Collection;

    public function create(array $data) : SubscriptionModel;

    public function getLastForUser(int $user_id) : SubscriptionModel;

}
