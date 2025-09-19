<?php

namespace App\Services\Payment;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\Exceptions\ErrorException;
use App\Payment\Methods\CashPayment;
use App\Payment\Methods\StripePayment;
use App\Payment\Visitors\PaymentProcessor;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionPaymentRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Services\SuperAdmin\SubscriptionRequestService;
use App\Services\SuperAdmin\SubscriptionService;
use App\Types\PaymentStatus;
use App\Types\PaymentType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected SubscriptionPaymentRepositoryInterface $subscriptionPaymentRepository,
        protected SubscriptionRequestService  $subscriptionRequestService,
        protected PowerGeneratorRepositoryInterface $powerGeneratorRepository,
    )
    {
        //
    }

    public function createStripeCheckout(int $subscriptionRequest_id)
    {
        $payment=$this->subscriptionPaymentRepository->findWhere(['subscriptionRequest_id'=>$subscriptionRequest_id]);
        if (! $payment)
        {
            throw new ErrorException(__('payment.notFound'),ApiCode::NOT_FOUND);
        }
        $processor = new PaymentProcessor();
        $stripePayment = new StripePayment(null, $payment->amount*100,"Plan subscription");
        $result = $stripePayment->accept($processor);
        $this->subscriptionPaymentRepository->update($payment,['session_id'=>$result['session_id'],'type'=>PaymentType::Stripe]);
        return $this->success($result,__('payment.create'),ApiCode::CREATED);
    }

    public function stripeSuccess(Request $request)
    {
        try{
            $processor = new PaymentProcessor();
            $stripePayment = new StripePayment($request->get('session_id'));
            $result = $stripePayment->accept($processor);
            DB::beginTransaction();
            $payment = $this->subscriptionPaymentRepository->findWhere(['session_id' => $result['session_id']]);
            if (!$payment) {
                throw new ErrorException(__('payment.notFound'), ApiCode::NOT_FOUND);
            }
            $payment = $this->subscriptionPaymentRepository->update($payment, [
                'date' => Carbon::now(), 'status' => PaymentStatus::Paid
            ]);
            $this->subscriptionRequestService->approve($payment->subscriptionRequest_id);
            DB::commit();
            return $this->success($result, __('payment.success'));
        }
        catch(\Throwable  $exception)
        {
            DB::rollBack();
            throw new ErrorException($exception->getMessage(), ApiCode::INTERNAL_SERVER_ERROR);

//            throw new ErrorException(__('messages.error.serverError'), ApiCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function stripeCancel(Request $request)
    {
        $processor = new PaymentProcessor();
        $payment = new StripePayment($request->get('session_id'));
        $result = $payment->accept($processor);
        $payment=$this->subscriptionPaymentRepository->findWhere(['session_id'=>$result['session_id']]);
        if (! $payment)
        {
            throw new ErrorException(__('payment.notFound'),ApiCode::NOT_FOUND);
        }
       $this->subscriptionPaymentRepository->update($payment,[
            'status'=>PaymentStatus::Cancelled
        ]);
        return $this->success($result, __('payment.cancel'));
    }

    public function handleCashPayment($subscriptionRequest_id)
    {
        $processor = new PaymentProcessor();
        $payment = new CashPayment();
        $result = $payment->accept($processor);
        $payment=$this->subscriptionPaymentRepository->findWhere(['subscriptionRequest_id'=>$subscriptionRequest_id]);
        if (! $payment)
        {
            throw new ErrorException(__('payment.notFound'),ApiCode::NOT_FOUND);
        }
        $this->subscriptionPaymentRepository->update($payment,['type'=>PaymentType::Cash,'status'=>PaymentStatus::Pending]);
        return $this->success($result,__('payment.cash'));
    }

    public function getTotalForGenerator($generator_id)
    {
        $generator=$this->powerGeneratorRepository->find($generator_id);
        if (! $generator)
        {
            throw new ErrorException(__('powerGenerator.notFound'),ApiCode::NOT_FOUND);
        }
        $user_id=$generator->user->id;
        $payments=$this->subscriptionPaymentRepository->getTotalForGenerator($user_id);
        return $payments;
    }
}
