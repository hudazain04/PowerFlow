<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\Payment as PaymentModel;

interface PaymentRepositoryInterface
{
    public function create(array $data) : PaymentModel;

    public function findWhereLatest(array $wheres) : ?PaymentModel;

    public function findWhere(array $wheres) : ?PaymentModel;

    public function update(PaymentModel $payment, array $data) : PaymentModel;

}
