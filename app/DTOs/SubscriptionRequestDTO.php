<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;
use Carbon\Carbon;

class SubscriptionRequestDTO extends SimpleDTO
{
    public ?int $id;
    public int $period;
    public string $type;
    public string $location;
    public ?UserDTO $user;
    public ?PlanPriceDTO $planPrice;
    public ?PlanDTO $plan;
    public ?PowerGeneratorDTO $powerGenerator;
    public ?Carbon $created_at;

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'id'=> new IntegerCast(),
            'period'=> new IntegerCast(),
            'created_at'=>new CarbonCast(),

        ];
    }
}
