<?php

namespace App\Repositories\interfaces\Admin;
use Illuminate\Support\Collection;

interface PowerGeneratorRepositoryInterface
{
    public function count() : int;

    public function create(array $data);

    public function find(int $id);

    public function getForPlan($plan_id , array $filters) : Collection;

    public function getAll(array $filters) : Collection;

}
