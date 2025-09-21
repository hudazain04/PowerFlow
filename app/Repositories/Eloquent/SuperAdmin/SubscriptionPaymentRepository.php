<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\Models\SubscriptionPayment as SubscriptionPaymentModel;
use App\Repositories\interfaces\SuperAdmin\SubscriptionPaymentRepositoryInterface;
use App\Types\PaymentStatus;

class SubscriptionPaymentRepository implements SubscriptionPaymentRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(array $data): SubscriptionPaymentModel
    {
       $payment=SubscriptionPaymentModel::create($data);
       return $payment;
    }

    public function update(SubscriptionPaymentModel $subscriptionPayment, array $data): SubscriptionPaymentModel
    {
        $subscriptionPayment->update($data);
        $subscriptionPayment->save();
        return $subscriptionPayment;
    }

    public function findWhere(array $wheres): ?SubscriptionPaymentModel
    {
        $payment=SubscriptionPaymentModel::where($wheres)->first();
        return $payment;
    }

    public function getTotalForGenerator($user_id): int
    {
        return SubscriptionPaymentModel::where(['user_id'=> $user_id,'status'=>PaymentStatus::Paid])
            ->sum('amount');
    }
}
