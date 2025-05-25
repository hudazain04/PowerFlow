<?php

namespace App\Repositories\interfaces\User;

interface CustomerRequestRepositoryInterface
{
    public function createRequest(array $data);
    public function update(int $id,array $data) :  bool;
    public function find(int $id);
    public function getPending();
}
