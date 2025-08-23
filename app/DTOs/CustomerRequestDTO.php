<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class CustomerRequestDTO extends SimpleDTO
{
    public ?int $user_id;
    public ?int $generator_id;
    public ?int $box_id;
    public ?string $user_notes;
    public ?string $admin_notes;
    public ?string $spendingType;
    public ?string $status;




    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'generator_id'=>new IntegerCast(),
            'box_id'=>new IntegerCast(),
        ];
    }
}
