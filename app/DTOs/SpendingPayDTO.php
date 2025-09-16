<?php

namespace App\DTOs;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class SpendingPayDTO extends SimpleDTO
{
    public ?int $id;
    public ?int $kilos;
    public ?Carbon $date;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'kilos'=>new IntegerCast(),
            'date'=>new CarbonCast(),

        ];
    }
}
