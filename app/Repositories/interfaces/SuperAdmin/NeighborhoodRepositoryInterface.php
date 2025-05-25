<?php

namespace App\Repositories\interfaces\SuperAdmin;

interface NeighborhoodRepositoryInterface
{
    public function create(array $data);
    public function findById(int $id);
    public function listAll();
}
