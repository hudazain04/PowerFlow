<?php

namespace App\DTOs;

use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Data;


//=======

//use WendellAdriel\ValidatedDTO\Casting\BooleanCast;
//use WendellAdriel\ValidatedDTO\SimpleDTO;

class UserDTO extends Data

{
//    public ?int $id;
//    public string $first_name;
//    public string $last_name;
//    public ?string $fullName;
//    public string $email;
//    public int $phone_number;
//    public string $password;
//    public ? string $role;
//    public ? bool $blocked;

    public function __construct(
        public ?string  $first_name = null,
        public ?string $last_name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?int    $phone_number = null,
        public ? bool $blocked,
        public ? string $role,
        public ?string $fullName,
        public ?int $id,
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
