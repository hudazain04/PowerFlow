<?php

namespace App\Repositories\interfaces\Admin;

interface PowerGeneratorRepositoryInterface
{
    public function count() : int;
    public function create(array $data);
    public function find(int $id);
}
