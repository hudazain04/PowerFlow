<?php

namespace App\Repositories\Eloquent;


use AllowDynamicProperties;
use App\DTOs\UserDTO;
use App\Models\User;
use App\Models\User as UserModel;
use App\Repositories\interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $model;
    public function __construct(UserModel $model){
        $this->model=$model;
    }
    public function createUser(UserDTO $dto): UserModel
    {

        return $this->model->create($dto->toCreateArray());
    }

    public function findUserByEmail(string $email): ?UserModel
    {
        return $this->model->where('email',$email)->first();
    }
    public function findUserBy(string $email){

    }

    public function findById(int $id): ?UserModel
    {
       return $this->model->find($id);
    }

    public function update(UserModel $user, array $data): UserModel
    {
       $user->update($data);
        return $user;
    }

    public function delete(UserModel $user): bool
    {
        return $user->delete();
    }

    public function assignRole(UserModel $user, string $role): void
    {
       $user->assignRole($role);
    }

    public function removeRole(UserModel $user, string $role): void
    {
        $user->removeRole($role);
    }

    public function count(): int
    {
        return UserModel::count();
    }


    public function blockedCount(): int
    {
        return UserModel::where('blocked',true)->count();

    }

    public function getRelations(User $user, array $relations): UserModel
    {
        $user->load($relations);
        return $user;
    }
}
