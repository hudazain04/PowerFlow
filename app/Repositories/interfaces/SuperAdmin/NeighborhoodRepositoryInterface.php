<?php

namespace App\Repositories\interfaces\SuperAdmin;

interface NeighborhoodRepositoryInterface
{
    public function create(array $data);
    public function findById(int $id);
    public function listAll();
    public function update(int $id, array $data);
    public function delete(int $id);
}
