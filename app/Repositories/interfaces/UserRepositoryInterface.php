<?php

namespace App\Repositories\interfaces;

use App\DTOs\UserDTO;
use App\Models\User;
use Spatie\Permission\Models\Role;

interface UserRepositoryInterface
{
  public function createUser(UserDTO $dto): ?User ;
  public function findUserByEmail(string $email): ?User ;
  public function findById(int $id): ?User;
  public function update(User $user,array $data): ?User;
  public function delete(User $user): bool;
  public function assignRole(User $user,string $role): void;
  public function removeRole(User $user,string $role): void;
    public function count() : int;
    public function blockedCount() : int;

}
