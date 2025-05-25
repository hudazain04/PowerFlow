<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class FeatureDTO extends SimpleDTO
{
    public ?int $id;
    public string $key;
    public ?int $value;
    public string $description;

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'id'=>new IntegerCast(),
            'value'=>new IntegerCast(),
        ];
    }
}
