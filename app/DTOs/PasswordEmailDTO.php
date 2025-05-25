<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class PasswordEmailDTO extends Data
{
    public function __construct(
         public readonly string $email
                                    ){

}
}
