<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class AreaDTO extends Data
{
public function __construct(
    public  int $generator_id,
    public  string $name,
){

}
}
