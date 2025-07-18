<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\SimpleDTO;
use Carbon\Carbon;


class SubscribedGeneratorDTO extends SimpleDTO
{
    public ?int $id;
    public string $name;
    public ?int $phone;
    public ?Carbon $expired_at;

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}
