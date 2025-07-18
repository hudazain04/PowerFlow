<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class AssignBoxToAreaDTO extends Data
{
    public function __construct(
        public readonly int $box_id,
        public readonly int $area_id
    ) {}
}
