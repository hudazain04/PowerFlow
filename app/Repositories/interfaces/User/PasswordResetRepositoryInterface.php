<?php

namespace App\Repositories\interfaces\User;

use App\DTOs\UserDTO;
use App\Models\User;

interface PasswordResetRepositoryInterface
{
public function findEmail(string $email): ?User;

public function updatePassword(User $user,string $password): bool;



}
