<?php

namespace App\DTOs;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Data;

class UserDTO extends Data
{

    public function __construct(
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?int    $phone_number = null,
    )
    {
    }

    public function toCreateArray(): array{
        return [
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'password'=>Hash::make($this->password),
            'phone_number'=>$this->phone_number
        ];
    }


}
