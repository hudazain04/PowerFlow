<?php

namespace App\DTOs;
use Spatie\LaravelData\Data;

class ElectricalBoxDTO extends Data
{
    public function __construct(
        public readonly int $generator_id,
        public readonly string $number,
        public readonly array $location,
        public readonly int $capacity
    ) {}
}
