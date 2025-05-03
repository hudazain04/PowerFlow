<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class PlanPriceDTO extends SimpleDTO
{
    public ?int $id;
    public ?int $price;
    public int $discount;
    public int $period;
    public int $plan_id;

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'id'=>new IntegerCast(),
            'price' => new IntegerCast(),
            'discount' => new IntegerCast(),
            'period' => new IntegerCast(),
            'plan_id' => new IntegerCast(),
        ];
    }
}
