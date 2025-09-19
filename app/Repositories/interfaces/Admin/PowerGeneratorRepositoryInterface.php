<?php

namespace App\Repositories\interfaces\Admin;
use App\Models\PowerGenerator as PowerGeneratorModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PowerGeneratorRepositoryInterface
{
    public function count() : int;

    public function create(array $data);

    public function update(PowerGeneratorModel $powerGenerator,array $data) : PowerGeneratorModel;

    public function find(int $id);

    public function getForPlan($plan_id , array $filters) : LengthAwarePaginator;

    public function getAll(array $filters) : LengthAwarePaginator;

    public function getRelationCount(PowerGeneratorModel $powerGenerator, string $relation) : int;
}
