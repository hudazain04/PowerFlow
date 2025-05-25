<?php

namespace App\DTOs;
use Spatie\LaravelData\Data;

class CounterDTO extends Data
{
    public function __construct(
        public readonly int $request_id,
        public readonly int $user_id,
        public readonly int $generator_id,
        public readonly string $counterNumber,
        public readonly ?string $qrCode = null
    ) {}
}
