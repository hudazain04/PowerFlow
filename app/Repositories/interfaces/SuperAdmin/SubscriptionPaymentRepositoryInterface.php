<?php

namespace App\Repositories\interfaces\SuperAdmin;

use App\Models\SubscriptionPayment as SubscriptionPaymentModel ;

interface SubscriptionPaymentRepositoryInterface
{
    public function create(array $data) : SubscriptionPaymentModel;

    public function update(SubscriptionPaymentModel $subscriptionPayment,array $data)  : SubscriptionPaymentModel;

    public function findWhere(array $wheres) : ?SubscriptionPaymentModel;

    public function getTotalForGenerator($user_id) : int;
}
