<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\Counter;

interface CounterRepositoryInterface
{
    public function create(array $data): Counter;
    public function find(int $id): ?Counter;
    public function update(int $id, array $data): bool;
    public function delete(int $id) : bool;
    public function getCounters(int $generator_id);
   public function getUserCount(int $generator_id);
   public function getTotalConsumption(int $generator_id);


}
