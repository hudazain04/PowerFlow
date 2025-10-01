<?php

namespace App\Services\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\GeneratorSettingDTO;
use App\DTOs\PlanDTO;
use App\DTOs\PlanPriceDTO;
use App\DTOs\PowerGeneratorDTO;
use App\DTOs\SubscriptionDTO;
use App\DTOs\SubscriptionRequestDTO;
use App\DTOs\UserDTO;
use App\Exceptions\ErrorException;
use App\Http\Resources\SubscriptionRequestResource;
use App\Jobs\AfterPaymentReminderJob;
use App\Jobs\SetExpiredSubscriptionJob;
use App\Mail\SendSubscriptionRequestStatusMail;
use App\Models\User;
use App\Models\User as UserModel;
use App\Repositories\interfaces\Admin\GeneratorSettingRepositoryInterface;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionPaymentRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;
use App\Repositories\interfaces\UserRepositoryInterface;
use App\Services\NotificationService;
use App\Services\Payment\PaymentService;
use App\Types\GeneratorRequests;
use App\Types\PaymentStatus;
use App\Types\PaymentType;
use App\Types\SubscriptionTypes;
use App\Types\UserTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class SubscriptionRequestService
{
    use ApiResponse;

    public function __construct(
        protected SubscriptionRequestRepositoryInterface $subscriptionRequestRepository,
        protected PlanPriceRepositoryInterface $planPriceRepository,
        protected PowerGeneratorRepositoryInterface $powerGeneratorRepository,
        protected SubscriptionRepositoryInterface $subscriptionRepository,
        protected UserRepositoryInterface $userRepository,
        protected SubscriptionPaymentRepositoryInterface $subscriptionPaymentRepository,
        protected GeneratorSettingRepositoryInterface $generatorSettingRepository,
        protected PlanPriceService $planPriceService,
        protected PaymentService $paymentService,
    )
    {
    }

    public function getLastFive()
    {
        $subscriptionRequests=$this->subscriptionRequestRepository->getLastFive();
        return $this->success(SubscriptionRequestResource::collection($subscriptionRequests),__('messages.success'));
    }

    public function store(SubscriptionRequestDTO $requestDTO)
    {
        $planPrice=$this->planPriceRepository->find($requestDTO->planPrice_id);
        if (!$planPrice)
        {
            throw new ErrorException(__('planPrice.notFound'),ApiCode::NOT_FOUND);
        }
        $requestDTO->period=$planPrice->period;
        $requestDTO->status=GeneratorRequests::PENDING;
//        dd($requestDTO);
        $subscriptionRequest=$this->subscriptionRequestRepository->create($requestDTO->toArray());
        $payment=$this->subscriptionPaymentRepository->create([
            'user_id'=>$requestDTO->user_id,
            'status'=>PaymentStatus::Pending,
            'amount'=>$planPrice->price,
            'subscriptionRequest_id'=>$subscriptionRequest->id,
        ]);
        return $this->success(SubscriptionRequestResource::make($subscriptionRequest),__('subscriptionRequest.create'));
    }

    public function getAll(Request $request)
    {
        $requests=$this->subscriptionRequestRepository->getAll([ 'status' => $request->query('status')]);
        return $this->successWithPagination(SubscriptionRequestResource::collection($requests),__('messages.success'));
    }

    public function approve( int $requestId)
    {
        try{
            DB::beginTransaction();
            $request = $this->subscriptionRequestRepository->find($requestId);
            if (!$request) {
                throw new ErrorException(__('subscriptionRequest.notFound'), ApiCode::NOT_FOUND);
            }
            $request = $this->subscriptionRequestRepository->update($request, ['status' => GeneratorRequests::APPROVED]);
            $user=$this->userRepository->findById($request->user_id);
            if ($request->type===SubscriptionTypes::NewPlan)
            {
                $powerGeneratorDTO = new PowerGeneratorDTO();
                $powerGeneratorDTO->name = $request->name;
                $powerGeneratorDTO->location = $request->location;
                $powerGeneratorDTO->user_id = $request->user_id;
                $generator = $this->powerGeneratorRepository->create($powerGeneratorDTO->toArray());
                $settingDTO=new GeneratorSettingDTO();
                $settingDTO->generator_id=$generator->id;
                $settingDTO->day=$request->day;
                $settingDTO->spendingType=$request->spendingType;
                $settingDTO->afterPaymentFrequency=$request->afterPaymentFrequency;
                $settingDTO->kiloPrice=$request->kiloPrice;
                $settingDTO->nextDueDate=Carbon::now()
                    ->addWeeks($request->afterPaymentFrequency)
                    ->next($request->day);
                $this->generatorSettingRepository->create($settingDTO->toArray());
                $this->userRepository->updateRole($user,UserTypes::ADMIN);
                if ($request->phones)
                {
                    foreach ($request->phones as $phone) {
                        $generator->phones()->create([
                            'number' => $phone,
                            'generator_id'=>$generator->id,
                        ]);
                    }
                }
                $reminderDate = $settingDTO->nextDueDate->copy()->subDay()->startOfDay();
                AfterPaymentReminderJob::dispatchAfterResponse($generator)
                    ->delay($reminderDate);
                $start_time=Carbon::now();
            }
            if ($request->type===SubscriptionTypes::Upgrade)
            {
                $lastSubscription=$this->subscriptionRepository->getLastForGenerator($user->powerGenerator->id);
                $subscription=$this->subscriptionRepository->update($lastSubscription,['expired_at'=>Carbon::now()]);
                $generator=$user->powerGenerator;
                $start_time=Carbon::now();

            }
            if ($request->type===SubscriptionTypes::Renew)
            {
                $lastSubscription=$this->subscriptionRepository->getLastForGenerator($user->powerGenerator->id);
                $generator=$user->powerGenerator;
                $start_time=($lastSubscription->start_time)
                ->addWeeks($lastSubscription->period);
            }

            $planPrice = $this->subscriptionRequestRepository->getRelations($request, ['planprice'])->planPrice;
            $subscriptionDTO = new SubscriptionDTO();
            $subscriptionDTO->start_time = $start_time;
            $subscriptionDTO->period = $request->period;
            $subscriptionDTO->planPrice_id = $planPrice->id;
            $subscriptionDTO->price = $planPrice->price;
            $subscriptionDTO->generator_id = $generator->id;
            $subscription=$this->subscriptionRepository->create($subscriptionDTO->toArray());
            $delay=($subscription->start_time)->addWeeks($subscription->period);
            SetExpiredSubscriptionJob::dispatchAfterResponse($subscription)->delay($delay);

            $payment=$this->subscriptionPaymentRepository->findWhere(['subscriptionRequest_id'=>$requestId]);
            if (! $payment)
            {
                throw new ErrorException(__('payment.notFound'),ApiCode::NOT_FOUND);
            }
            if ($payment->type == PaymentType::Cash)
            {
                $this->subscriptionPaymentRepository->update($payment,[
                    'date'=>Carbon::now(),'status'=>PaymentStatus::Paid
                ]);
            }
            if ($payment->type == null)
            {
                $this->paymentService->handleCashPayment($requestId);
            }
            DB::commit();
            $user=$request->user;
            Mail::to($user->email)->send(new SendSubscriptionRequestStatusMail(GeneratorRequests::APPROVED));
            return ['success'=>true];

        }
        catch (\Throwable $exception) {
            DB::rollBack();
            throw new ErrorException($exception->getMessage(), ApiCode::INTERNAL_SERVER_ERROR);

//            throw new ErrorException(__('messages.error.serverError'), ApiCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function reject($admin_notes,int $requestId)
    {
        $request=$this->subscriptionRequestRepository->find($requestId);
        if (! $request)
        {
            throw new ErrorException(__('subscriptionRequest.notFound'),ApiCode::NOT_FOUND);
        }
        $request=$this->subscriptionRequestRepository->update($request,['status'=>GeneratorRequests::REJECTED,'admin_notes'=>$admin_notes]);
        $user=$request->user;
//        dd($request->admin_notes);
        Mail::to($user->email)->send(new SendSubscriptionRequestStatusMail(GeneratorRequests::REJECTED, $request->admin_notes));
        return $this->success(null,__('subscriptionRequest.reject'));

    }





}
