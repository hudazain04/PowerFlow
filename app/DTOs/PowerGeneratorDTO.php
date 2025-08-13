<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class PowerGeneratorDTO extends SimpleDTO
{
    public ?int $id;
    public string $name;
    public string $location;
    public ?int $user_id;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'user_id'=>new IntegerCast(),
        ];
    }
}
