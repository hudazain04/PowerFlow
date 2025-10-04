<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\SubscriptionRequestDTO;
use App\Exceptions\ErrorException;
use App\Http\Resources\SubscriptionResource;
use App\Models\User as UserModel;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionPaymentRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;
use App\Repositories\interfaces\UserRepositoryInterface;
use App\Types\GeneratorRequests;
use App\Types\PaymentStatus;
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
        protected SubscriptionPaymentRepositoryInterface $subscriptionPaymentRepository,
        protected PlanPriceService $planPriceService,
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
            $subscription=$this->subscriptionRepository->update($subscription,['expired_at'=>true]);
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
//        $user=$this->userRepository->getRelations($user,['powerGenerator']);
        $subscriptionRequestDTO->period=$planPrice->period;
        $subscriptionRequestDTO->status=GeneratorRequests::PENDING;
        $subscriptionRequest=$this->subscriptionRequestRepository->create($subscriptionRequestDTO->toArray());
        $lastSubscription=$this->subscriptionRepository->getLastForGenerator($user->powerGenerator->id);
        $lastPlanPrice=$lastSubscription->planPrice;
        $usedMonths = now()->diffInMonths($lastSubscription->start_time);
        $monthlyPrice=$lastPlanPrice->plan->monthlyPrice;
        $remainingPrice=($lastPlanPrice->price)-($this->planPriceService->calculateTotalPrice($monthlyPrice,$lastPlanPrice->discount,$usedMonths));
        $amount=($planPrice->price)-($remainingPrice);
        $payment=$this->subscriptionPaymentRepository->create([
            'user_id'=>$subscriptionRequest->user_id,
            'status'=>PaymentStatus::Pending,
            'amount'=>$amount,
            'subscriptionRequest_id'=>$subscriptionRequest->id,
        ]);
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
//        $user=$this->userRepository->getRelations($user,['powerGenerator']);
        $subscriptionRequestDTO->period=$planPrice->period;
        $subscriptionRequestDTO->status=GeneratorRequests::PENDING;
        $subscriptionRequest=$this->subscriptionRequestRepository->create($subscriptionRequestDTO->toArray());
        $payment=$this->subscriptionPaymentRepository->create([
            'user_id'=>$subscriptionRequest->user_id,
            'status'=>PaymentStatus::Pending,
            'amount'=>$planPrice->price,
            'subscriptionRequest_id'=>$subscriptionRequest->id,
        ]);
        return $this->success(null,__('subscriptionRequest.create'));

    }

    public function getLastSubscription(int $generator_id)
    {
     $subscription=$this->subscriptionRepository->getLastForGenerator($generator_id);
     $subscription=$this->subscriptionRepository->getRelations($subscription,['planPrice.plan']);
     return $this->success(SubscriptionResource::make($subscription),__('messages.success'));

    }

}
