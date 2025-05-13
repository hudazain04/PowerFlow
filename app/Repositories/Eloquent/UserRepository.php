<?php

namespace App\Repositories\Eloquent;

use AllowDynamicProperties;
use App\DTOs\UserDTO;
use App\Models\User;
use App\Repositories\interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $model;
    public function __construct(User $model){
        $this->model=$model;
    }
    public function createUser(UserDTO $dto): User
    {

        return $this->model->create($dto->toCreateArray());
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->model->where('email',$email)->first();
    }

    public function findById(int $id): ?User
    {
       return $this->model->find($id);
    }

    public function update(User $user, array $data): User
    {
       $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function assignRole(User $user, string $role): void
    {
       $user->assignRole($role);
    }

    public function removeRole(User $user, string $role): void
    {
        $user->removeRole($role);
    }
}
