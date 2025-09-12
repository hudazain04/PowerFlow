<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\Counter;
use App\Models\Payment;
use App\Models\Spending;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
interface CounterRepositoryInterface
{
    public function create(array $data): Counter;
    public function find(int $id): ?Counter;

    public function update(int $id, array $data): bool;

    public function delete(int $id) : bool;

    public function getCounters(int $generator_id,?array $filters=[]);

    public function getUserCount(int $generator_id);

    public function getUserCountForGenerator(int $generator_id);

    public function getTotalConsumption(int $generator_id);

    public function getWhere(array $wheres) : Counter;

    public function getRelations(Counter $counter,array $relations) : Counter;

    public function get($generator_id,?array $filters=[]) : Collection;
    public function getUserWithCounters(int $userId, int $generatorId);

    public function latestPayment(Counter $counter) : ?Payment;

    public function latestSpending(Counter $counter) : ?Spending;
}
