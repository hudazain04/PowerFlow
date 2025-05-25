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

    public function __construct(
        public ?int    $id = null,
        public ?string $name = null,
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?int    $phone_number = null,
        public ?int    $generator_id = null
    )
    {
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'phone_number' => $this->phone_number,
        ];

        if ($this->name) {
            $data['name'] = $this->name;
        } elseif ($this->first_name && $this->last_name) {
            $data['name'] = $this->first_name . ' ' . $this->last_name;
        }

        return array_filter($data, fn($value) => !is_null($value));
    }


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
