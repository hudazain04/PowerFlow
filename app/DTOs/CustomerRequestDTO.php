<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class CustomerRequestDTO extends Data
{
    public function __construct(
//        public readonly int $user_id,
        public readonly int $generator_id,
//        public readonly ?string $userNotes,
//        public readonly ?string $adminNotes,
//        public readonly ?string $address,
//        public readonly ?float $estimatedUsage
    ) {}
}
