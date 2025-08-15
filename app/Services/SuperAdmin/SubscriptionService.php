<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\SubscriptionRequestDTO;
use App\Exceptions\ErrorException;
use App\Models\User as UserModel;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;
use App\Repositories\interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected SubscriptionRepositoryInterface $subscriptionRepository,
        protected UserRepositoryInterface $userRepository,
        protected SubscriptionRequestRepositoryInterface $subscriptionRequestRepository,
        protected PlanPriceRepositoryInterface $planPriceRepository,
    )
    {
        //
    }

    public function cancel(UserModel $user)
    {
        try {
            DB::beginTransaction();
            $generator=$this->userRepository->getRelations($user,['powerGenerator'])->powerGenerator;
            $subscription=$this->subscriptionRepository->getLastForGenerator($generator->id);
            if (! $subscription)
            {
                throw new ErrorException(__('subscription.notFoundForUser'),ApiCode::NOT_FOUND);
            }
//        dd($subscription);
            $subscription=$this->subscriptionRepository->update($subscription,['expired_at'=>Carbon::now()]);
            DB::commit();
            return $this->success(null,__('subscription.delete'));
        }
        catch (\Throwable $exception)
        {
            DB::rollBack();
            throw new ErrorException(__('messages.error.serverError'),ApiCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function upgrade(SubscriptionRequestDTO $subscriptionRequestDTO)
    {
        $user=$this->userRepository->findById($subscriptionRequestDTO->user_id);
        if (!$user)
        {
            throw new ErrorException(__('auth.userNotFound'),ApiCode::NOT_FOUND);
        }
        $planPrice=$this->planPriceRepository->find($subscriptionRequestDTO->planPrice_id);
        if (!$planPrice)
        {
            throw new ErrorException(__('planPrice.notFound'),ApiCode::NOT_FOUND);
        }
        $user=$this->userRepository->getRelations(['powerGenerator']);
        $subscriptionRequestDTO->name=$user->powerGenerator->name;
        $subscriptionRequestDTO->location=$user->powerGenerator->location;
        $subscriptionRequestDTO->period=$planPrice->period;
        $subscription=$this->subscriptionRepository->getLastForGenerator($user->powerGenerator->id);
        if (! $subscription)
        {
            throw new ErrorException(__('subscription.notFoundForUser'),ApiCode::NOT_FOUND);
        }
//        dd($subscription);
        $subscription=$this->subscriptionRepository->update($subscription,['expired_at'=>Carbon::now()]);
        $subscriptionRequest=$this->subscriptionRequestRepository->create($subscriptionRequestDTO->toArray());
        return $this->success(null,__('subscriptionRequest.create'));

    }

    public function renew(SubscriptionRequestDTO $subscriptionRequestDTO)
    {
        $user=$this->userRepository->findById($subscriptionRequestDTO->user_id);
        if (!$user)
        {
            throw new ErrorException(__('auth.userNotFound'),ApiCode::NOT_FOUND);
        }
        $planPrice=$this->planPriceRepository->find($subscriptionRequestDTO->planPrice_id);
        if (!$planPrice)
        {
            throw new ErrorException(__('planPrice.notFound'),ApiCode::NOT_FOUND);
        }
        $user=$this->userRepository->getRelations($user,['powerGenerator']);
//        dd($user->powerGenerator);
        $subscriptionRequestDTO->name=$user->powerGenerator->name;
        $subscriptionRequestDTO->location=$user->powerGenerator->location;
        $subscriptionRequestDTO->period=$planPrice->period;
        $subscriptionRequest=$this->subscriptionRequestRepository->create($subscriptionRequestDTO->toArray());
        return $this->success(null,__('subscriptionRequest.create'));

    }

}
