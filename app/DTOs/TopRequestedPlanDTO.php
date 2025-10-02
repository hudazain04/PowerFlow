<?php

namespace App\DTOs;

use App\Models\Plan;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class TopRequestedPlanDTO extends SimpleDTO
{
    public Plan  $plan;
    public ?int $count;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}
