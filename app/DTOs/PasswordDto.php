<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class PasswordDto extends Data
{
  public function __construct(
      public readonly string $token,
      public readonly string $password,
      public readonly string $password_confirmation
  )
  {
  }
}
