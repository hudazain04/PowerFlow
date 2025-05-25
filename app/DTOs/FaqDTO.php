<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class FaqDTO extends Data
{
  PUBLIC FUNCTION __construct(
      public string $question,
      public string $answer,
      public string $role,

  )
  {
  }
}
