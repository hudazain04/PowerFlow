<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\SimpleDTO;

class PowerGeneratorDTO extends SimpleDTO
{
    public ?int $id;
    public string $name;
    public string $location;
    public ?UserDTO $user;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}
