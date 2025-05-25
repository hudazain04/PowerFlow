<?php

namespace App\DTOs;
use Spatie\LaravelData\Data;

class NeighborhoodDTO extends Data
{
  public function __construct(
   public string $name,
   public string $location
  ){}
}
