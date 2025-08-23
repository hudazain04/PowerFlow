<?php

namespace App\Repositories\interfaces\User;

use App\Models\CustomerRequest  as CustomerRequestModel;

interface CustomerRequestRepositoryInterface
{
    public function createRequest(array $data);
    public function update(int $id,array $data) :  bool;
    public function find(int $id);
    public function getPending(int $generator_id);
    public function getWithRelations(CustomerRequestModel $request ,array $relations=['*']) : CustomerRequestModel;
}
