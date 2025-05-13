<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\BooleanCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class UserDTO extends SimpleDTO
{
    public ?int $id;
    public string $first_name;
    public string $last_name;
    public ?string $fullName;
    public string $email;
    public int $phone_number;
    public ?string $password;
    public string $role;
    public bool $blocked;


    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'blocked'=>new BooleanCast(),
        ];
    }
}
