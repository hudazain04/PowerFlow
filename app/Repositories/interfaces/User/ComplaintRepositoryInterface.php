<?php

namespace App\Repositories\interfaces\User;
use App\Models\Complaint  as ComplaintModel;
use Illuminate\Database\Eloquent\Collection;

interface ComplaintRepositoryInterface
{
    public function create(array $data) : ComplaintModel;

    public function update(ComplaintModel $complaint,array $data) : ComplaintModel;

    public function find(int $id) : ?ComplaintModel;

    public function delete(ComplaintModel $complaint) :  bool;

    public function getAll(?array $filters=[]) : Collection;

    public function getRelations(ComplaintModel $complaint,array $relations=[]) : ComplaintModel;
}
