<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\Models\User as UserModel;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;

class SubscriptionService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected SubscriptionRepositoryInterface $subscriptionRepository,
    )
    {
        //
    }

    public function cancel(UserModel $user)
    {
        $subscription=$this->subscriptionRepository->getLastForUser($user->id);
        $subscription=$this->subscriptionRepository->softDelete($subscription);
        return $this->success(null,__('subscription.delete'));
    }
}
