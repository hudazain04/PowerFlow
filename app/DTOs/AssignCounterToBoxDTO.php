<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class AssignCounterToBoxDTO extends Data
{
    public function __construct(
        public readonly int $counter_id,
        public readonly int $box_id
    ) {}
}
