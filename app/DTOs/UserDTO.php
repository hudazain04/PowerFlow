<?php

namespace App\DTOs;
class UserDTO
{

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


}
