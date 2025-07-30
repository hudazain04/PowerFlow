<?php

namespace App\DTOs;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class SubscriptionDTO extends SimpleDTO
{
    public ?Carbon $start_time;
    public ?int $period;
    public ?int $price;
    public ?int $planPrice_id;
    public ?int $generator_id;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'start_time'=>new CarbonCast(),
            'planPrice_id'=>new IntegerCast(),
            'generator_id'=>new IntegerCast(),

        ];
    }
}
