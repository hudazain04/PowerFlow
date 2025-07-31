<?php

namespace App\Services\SuperAdmin;

use App\Models\User as UserModel;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;

class SubscriptionService
{
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
    }
}
