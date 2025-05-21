<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\SimpleDTO;

class AppInfoDTO extends SimpleDTO
{
    public ?int $id;
    public ?string $type;
    public string $content;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}
