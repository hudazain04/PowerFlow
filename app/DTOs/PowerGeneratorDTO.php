<?php

namespace App\DTOs;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class PowerGeneratorDTO extends SimpleDTO
{
    public ?int $id;
    public string $name;
    public ?string $email;
    public string $location;
    public ?string $phone;
    public ?Carbon $expired_at;
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
