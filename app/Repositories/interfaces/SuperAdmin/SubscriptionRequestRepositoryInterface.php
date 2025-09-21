<?php

namespace App\Repositories\interfaces\SuperAdmin;

use App\Types\SubscriptionTypes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\SubscriptionRequest as SubscriptionRequestModel;

interface SubscriptionRequestRepositoryInterface
{
    public function count() : int;

    public function getLastFive() : Collection;

    public function getAllWithPlan() : Collection;

    public function getRequestsCountForPlan(int $plan_id) : int;

    public function getRequestsForPlan(int $plan_id ,  ?string $type) : Collection;

    public function create(array $data) : SubscriptionRequestModel;

    public function getAll(array $filters=[]) : LengthAwarePaginator;

    public function find(int $id) : ?SubscriptionRequestModel;

    public function update(SubscriptionRequestModel $subscriptionRequest, array $data) : SubscriptionRequestModel;

    public function getRelations(SubscriptionRequestModel $subscriptionRequest , array $realtions) : SubscriptionRequestModel;
}
