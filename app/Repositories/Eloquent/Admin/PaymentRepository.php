<?php

namespace App\Repositories\Eloquent\Admin;
use App\Models\Payment;
use App\Models\Payment as PaymentModel;
use App\Repositories\interfaces\Admin\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{

    public function create(array $data): PaymentModel
    {
        $payment=PaymentModel::create($data);
        return $payment;
    }

    public function findWhereLatest(array $wheres): ?PaymentModel
    {
       $payment=PaymentModel::where($wheres)->latest()->first();
       return $payment;
    }
    public function findWhere(array $wheres): ?PaymentModel
    {
        $payment=PaymentModel::where($wheres)->first();
        return $payment;
    }

    public function update(PaymentModel $payment, array $data): PaymentModel
    {
        $payment->update($data);
        $payment->save();
        return  $payment;
    }


    public function getForGenerator($generator_id, ?array $filters = [])
    {

        $payments = Payment::filter($filters)->whereHas('counter', function ($q) use ($generator_id) {
            $q->where('generator_id', $generator_id);
        });
        return  $payments;
    }
}
