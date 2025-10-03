<?php

namespace App\Services;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\SpendingPayDTO;
use App\Events\AdminActionEvent;
use App\Events\PaymentActionEvent;
use App\Exceptions\ErrorException;
use App\Http\Requests\Payment\SpendingPayRequest;
use App\Payment\Methods\CashPayment;
use App\Payment\Methods\StripePayment;
use App\Payment\Visitors\PaymentProcessor;
use App\Repositories\interfaces\Admin\ActionRepositoryInterface;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\Admin\PaymentRepositoryInterface;
use App\Repositories\interfaces\Admin\SpendingRepositoryInterface;
use App\Repositories\interfaces\SpendingPaymentRepositoryInterface;
use App\Services\Admin\ActionService;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use App\Types\CounterStatus;
use App\Types\PaymentStatus;
use App\Types\PaymentType;
use App\Types\SpendingTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpendingPaymentService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected SpendingRepositoryInterface $spendingRepository,
        protected PaymentRepositoryInterface $paymentRepository,
        protected CounterRepositoryInterface $counterRepository,
        protected ActionService $actionService,
    )
    {
        //
    }


    public function createStripeCheckout(SpendingPayDTO $dto,int $counter_id)
    {
        $counter=$this->counterRepository->find($counter_id);
        if (!$counter)
        {
            throw  new ErrorException(__('counter.notFound'),ApiCode::NOT_FOUND);
        }
        $currentSpending=$this->spendingRepository->getLastForCounter($counter_id);
        if ($counter->spendingType === SpendingTypes::Before)
        {
            $generatorSettings=$this->counterRepository->getRelations($counter,['powerGenerator.settings'])->powerGenerator->settings;
            $amount=$dto->kilos*$generatorSettings->kiloPrice;
        }
        elseif ($counter->spendingType === SpendingTypes::After)
        {
            $generatorSettings=$this->counterRepository->getRelations($counter,['powerGenerator.settings'])->powerGenerator->settings;
            $nextDueDate=$generatorSettings->nextDueDate;
            $today = Carbon::today();
            $yesterday = $nextDueDate->copy()->subDay();
            if (!($today->equalTo($nextDueDate) || $today->equalTo($yesterday)) ) {
                throw new ErrorException(__('payOnDueDate'),ApiCode::BAD_REQUEST);
            }
            if ($counter->status===CounterStatus::DisConnected)
            {
                throw new ErrorException(__('payWithCache'),ApiCode::BAD_REQUEST);
            }
            $lastPayment=$this->paymentRepository->findWhereLatest(['counter_id'=>$counter_id]);
            $amount=(($currentSpending ? $currentSpending->consume : 0 )-($lastPayment ? $lastPayment->current_spending : 0))/1000*$generatorSettings->kiloPrice;
        }
        $processor = new PaymentProcessor();
        $stripePayment = new StripePayment(null, $amount*100,"Spending Renew", route('spendingStripe.success'),route('spendingStripe.cancel'));
        $result = $stripePayment->accept($processor);
        $consume=$currentSpending ? $currentSpending->consume : 0;
        $payment=$this->paymentRepository->create([
            'amount'=>$amount,
            'current_spending'=>$consume,
            'next_spending'=>$dto->kilos ? $consume+($dto->kilos*1000): null,
            'counter_id'=>$counter_id,
            'status'=>PaymentStatus::Pending,
            'type'=>PaymentType::Stripe,
            'session_id'=>$result['session_id'],
        ]);

        return $this->success($result,__('spendingPayment.create'),ApiCode::CREATED);
    }

    public function stripeSuccess(Request $request)
    {
        try{
            $processor = new PaymentProcessor();
            $stripePayment = new StripePayment($request->get('session_id'));
            $result = $stripePayment->accept($processor);
            DB::beginTransaction();

            $payment = $this->paymentRepository->findWhere(['session_id' => $result['session_id']]);
            if (!$payment) {
                throw new ErrorException(__('spendingPayment.notFound'), ApiCode::NOT_FOUND);
            }
            $payment = $this->paymentRepository->update($payment, [
                'date' => Carbon::now(), 'status' => PaymentStatus::Paid
            ]);

            $this->checkCutCounter($payment->counter_id,$payment);

            DB::commit();
            return $this->success($result, __('spendingPayment.success'));
        }
        catch(\Throwable  $exception)
        {
            DB::rollBack();
//            throw new ErrorException($exception->getMessage(), ApiCode::INTERNAL_SERVER_ERROR);

            throw new ErrorException(__('messages.error.serverError'), ApiCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function checkCutCounter($spendingPayment,$counter_id)
    {
        $counter=$this->counterRepository->find($counter_id);
        if ($counter->status === CounterStatus::DisConnected)
        {
            $action=$this->actionService->create([
                'type'=>ActionTypes::Payment,
                'status'=>ComplaintStatusTypes::Assigned,
                'counter_id'=>$counter_id,
                'generator_id'=>$counter->generator_id,
                'relatedData'=>['payment'=>$spendingPayment],
            ]);

            event(new AdminActionEvent($counter->generator_id,$action));

        }
        return;

    }

    public function stripeCancel(Request $request)
    {
        $processor = new PaymentProcessor();
        $payment = new StripePayment($request->get('session_id'));
        $result = $payment->accept($processor);
        $payment=$this->paymentRepository->findWhere(['session_id'=>$result['session_id']]);
        if (! $payment)
        {
            throw new ErrorException(__('spendingPayment.notFound'),ApiCode::NOT_FOUND);
        }
        $this->paymentRepository->update($payment,[
            'status'=>PaymentStatus::Cancelled
        ]);
        return $this->success($result, __('spendingPayment.cancel'));
    }

    public function handleCashPayment(SpendingPayDTO $dto,$counter_id)
    {
        $counter=$this->counterRepository->find($counter_id);
        if (!$counter)
        {
            throw  new ErrorException(__('counter.notFound'),ApiCode::NOT_FOUND);
        }
        $currentSpending=$this->spendingRepository->getLastForCounter($counter_id);
        if ($counter->spendingType === SpendingTypes::Before)
        {
            $generatorSettings=$this->counterRepository->getRelations($counter,['powerGenerator.settings'])->powerGenerator->settings;
            $amount=$dto->kilos*$generatorSettings->kiloPrice;
        }
        elseif ($counter->spendingType === SpendingTypes::After)
        {
            $generatorSettings=$this->counterRepository->getRelations($counter,['powerGenerator.settings'])->powerGenerator->settings;
            $lastPayment=$this->paymentRepository->findWhereLatest(['counter_id'=>$counter_id]);
            $amount=(($currentSpending->consume)-($lastPayment->current_spending))/1000*$generatorSettings->kiloPrice;
        }
        $processor = new PaymentProcessor();
        $payment = new CashPayment();
        $result = $payment->accept($processor);
        $payment=$this->paymentRepository->create([
            'date'=>$dto->date ?? Carbon::now(),
            'amount'=>$amount,
            'current_spending'=>$currentSpending->consume,
            'next_spending'=>$dto->kilos ? $currentSpending->consume+($dto->kilos*1000): null,
            'counter_id'=>$counter_id,
            'status'=>PaymentStatus::Paid,
            'type'=>PaymentType::Cash,
//            'session_id'=>$result['session_id'],
        ]);
        $this->checkCutCounter($payment,$counter_id);
        return $this->success($result,__('spendingPayment.cash'));
    }

    public function getSpendingPayments($generator_id,Request $request)
    {
        $payments=$this->paymentRepository->getForGenerator($generator_id,[ 'date' => $request->query('date')]);
        return $payments;
    }
}
