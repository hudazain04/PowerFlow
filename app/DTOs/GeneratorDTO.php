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
    public int $phone,
    public string $status = GeneratorRequests::PENDING
)
{
}
}
