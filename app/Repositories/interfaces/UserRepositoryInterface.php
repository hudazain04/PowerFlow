<?php

namespace App\Repositories\interfaces;

use App\DTOs\UserDTO;
use App\Models\User as UserModel;
use Spatie\Permission\Models\Role;

interface UserRepositoryInterface
{
  public function createUser(UserDTO $dto): ?UserModel ;

  public function findUserByEmail(string $email): ?UserModel ;

  public function findById(int $id): ?UserModel;

  public function findUserBy(string $email);

  public function update(User $user,array $data): ?UserModel;

  public function delete(User $user): bool;

  public function assignRole(User $user,string $role): void;

  public function removeRole(User $user,string $role): void;

  public function count() : int;

  public function blockedCount() : int;

  public function getRelations(UserModel $user , array $relations) : UserModel;

}
