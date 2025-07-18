<?php

namespace App\Repositories\Eloquent\Admin;
use App\Models\Counter;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;

class CounterRepository implements CounterRepositoryInterface
{
   protected $model;
    public function __construct(Counter $counter){
        $this->model=$counter;
    }
    public function create(array $data): Counter
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Counter
    {
        return $this->model->find($id);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id',$id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}
