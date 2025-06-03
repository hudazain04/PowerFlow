<?php

namespace App\DTOs;
use Spatie\LaravelData\Data;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class CounterDTO extends SimpleDTO
{
        public  int $request_id;
        public  int $user_id;
        public  int $generator_id;
        public  string $counterNumber;
        public  ?string $qrCode = null;

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}
