<?php

namespace App\Repositories\Eloquent;

use AllowDynamicProperties;
use App\Models\User;
use App\Repositories\interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    protected $model;
    public function __construct(User $model){
        $this->model=$model;
    }
    public function createUser(array $data)
    {
        return $this->model->create($data);
    }

    public function findUserByEmail(string $email)
    {
        return $this->model->where('email',$email)->first();
    }
}
