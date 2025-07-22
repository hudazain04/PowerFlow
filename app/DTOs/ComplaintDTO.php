<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class ComplaintDTO extends SimpleDTO
{
    public ?int $id;
    public ?string $type;
    public string  $description;
    public ?string $status;
    public ?int  $counter_id;
    public ?int $employee_id;
    public ?int $user_id;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'counter_id'=>new IntegerCast(),
        ];
    }
}
