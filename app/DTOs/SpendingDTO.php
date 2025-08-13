<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\SimpleDTO;

class SpendingDTO extends SimpleDTO
{
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}
