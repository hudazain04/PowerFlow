<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\DTOs\GeneratorDTO;
use App\Models\GeneratorRequest;
use App\Models\User;
use App\Repositories\interfaces\SuperAdmin\GeneratorRequestRepositoryInterface;
use App\Types\GeneratorRequests;

class GeneratorRequestRepository implements GeneratorRequestRepositoryInterface
{
    protected $model;
    public function __construct(GeneratorRequest $model){
        $this->model=$model;
    }

    public function create(array $data): GeneratorRequest
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?GeneratorRequest
    {
        return $this->model->find($id)->first();
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function getPendingRequests()
    {
        return $this->model->where('status', GeneratorRequests::PENDING)->get();
    }

    public function getByStatus(string $status)
    {
        return $this->model->where('status', $status)->get();
    }


    public function Approve(int $id)
    {
        $user=$this->find($id);
        return $user->forceFill([
            'status'=>GeneratorRequests::APPROVED,
        ])->save();

    }

    public function Reject(int $id)
    {
        $user=$this->find($id);
        return $user->forceFill([
            'status'=>GeneratorRequests::REJECTED,
        ])->save();
    }

}
