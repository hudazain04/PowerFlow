<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;
use Carbon\Carbon;

class SubscriptionRequestDTO extends SimpleDTO
{
    public ?int $id;
    public ?int $period;
    public ?string $type;
    public ?string $name;
    public ?string $location;
    public ?int $user_id;
    public ?int $planPrice_id;
    public ?int $kiloPrice;
    public ?string $spendingType;
    public ?string $day;
    public ?array $phones;
    public ?int $afterPaymentFrequency;

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
            'planPrice_id'=>new IntegerCast(),
            'user_id'=>new IntegerCast(),
            'kiloPrice'=>new IntegerCast(),
            'afterPaymentFrequency'=>new IntegerCast(),


        ];
    }
}
