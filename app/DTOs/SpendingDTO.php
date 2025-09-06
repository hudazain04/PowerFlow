<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Validation\Rules\In;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class SpendingDTO extends SimpleDTO
{
    public ?int $id;
    public ?Carbon $date;
    public ?int $counter_id;
    public int $consume;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'counter_id'=>new IntegerCast(),
            'consume'=>new IntegerCast(),
            'date'=>new CarbonCast(),
        ];
    }
}
