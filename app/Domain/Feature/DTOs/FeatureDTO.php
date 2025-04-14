<?php

namespace App\Domain\Feature\DTOs;

use WendellAdriel\ValidatedDTO\SimpleDTO;

class FeatureDTO extends SimpleDTO
{
    public ?int $id;
    public string $name;

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}
