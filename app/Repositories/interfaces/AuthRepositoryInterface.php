<?php

namespace App\Repositories\interfaces;
use App\DTOs\UserDTO;
use App\Models\User;

interface AuthRepositoryInterface
{
  public function createUser(array $data) ;

  public function findUserByEmail(string $email) ;

}
