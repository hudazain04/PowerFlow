<?php

namespace App\DTOs;

use App\Types\GeneratorRequests;
use \Spatie\LaravelData\Data;

class GeneratorDTO extends Data
{

public function __construct(
    public string $first_name,
    public string $last_name,
    public string $generator_name,
    public string $generator_location,
    public string $phone,
    public ?string $user_id = null,
    public string $status = GeneratorRequests::PENDING,

)
{

}
    public  function createArray(): array
    {
        return [
            'first_name'=> $this->first_name,
            'last_name'=> $this->last_name,
            'generator_name'=> $this->generator_name,
            'generator_location'=> $this->generator_location,
            'phone'=> $this->phone,
            'user_id'=> auth()->user()->id,
            'status'=> GeneratorRequests::PENDING,
        ];

    }
}
{


    }
