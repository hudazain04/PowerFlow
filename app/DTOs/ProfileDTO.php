<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\SimpleDTO;

class ProfileDTO extends SimpleDTO
{
    public ?int $id;
    public string $first_name;
    public string $last_name;
    public ?string $email;
    public ?int  $phone_number;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}
