<?php

namespace App\Repositories\interfaces\User;
use App\Models\Complaint  as ComplaintModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ComplaintRepositoryInterface
{
    public function create(array $data) : ComplaintModel;

    public function update(ComplaintModel $complaint,array $data) : ComplaintModel;

    public function find(int $id) : ?ComplaintModel;

    public function delete(ComplaintModel $complaint) :  bool;

    public function getAll(?array $filters=[]) : LengthAwarePaginator;

    public function getRelations(ComplaintModel $complaint,array $relations=[]) : ComplaintModel;

    public function getEmployeeComplaints($employee,?array $filters=[]) : LengthAwarePaginator;

    public function getUserComplaints($user,?array $filters=[]) : LengthAwarePaginator;

}
