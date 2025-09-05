<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class SpendingPayDTO extends SimpleDTO
{
    public ?int $id;
    public ?int $kilos;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'kilos'=>new IntegerCast(),
        ];
    }
}
