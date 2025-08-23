<?php

namespace App\Repositories\interfaces;

use App\DTOs\UserDTO;
use App\Models\User as UserModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

interface UserRepositoryInterface
{
  public function createUser(UserDTO $dto): ?UserModel ;

  public function findUserByEmail(string $email): ?UserModel ;

  public function findById(int $id): ?UserModel;

  public function findUserBy(string $email);

  public function update(UserModel $user,array $data): ?UserModel;

  public function delete(UserModel $user): bool;

  public function assignRole(UserModel $user,string $role): void;

  public function removeRole(UserModel $user,string $role): void;

  public function count() : int;

  public function blockedCount() : int;

  public function getRelations(UserModel $user , array $relations) : UserModel;

  public function updateRole(UserModel $user, string $role) : void;

  public function getAll(array $filters) : LengthAwarePaginator;

}
