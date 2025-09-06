<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\Spending as SpendingModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SpendingRepositoryInterface
{
    public function create(array $data) : SpendingModel;

    public function update(SpendingModel $spending,array $data) : SpendingModel;

    public function find(int $id) : ?SpendingModel;

    public function delete(SpendingModel $spending) : bool;

    public function getAll(int $counter_id) : LengthAwarePaginator;

    public function getLastForCounter(int $counter_id) : SpendingModel;
}
