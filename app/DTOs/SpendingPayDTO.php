<?php

namespace App\DTOs;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Casting\FloatCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class SpendingPayDTO extends SimpleDTO
{
    public ?int $id;
    public ?float $kilos;
    public ?Carbon $date;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'kilos'=>new FloatCast(),
            'date'=>new CarbonCast(),

        ];
    }
}
