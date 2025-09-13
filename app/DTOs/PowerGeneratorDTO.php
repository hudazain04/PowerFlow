<?php

namespace App\DTOs;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class PowerGeneratorDTO extends SimpleDTO
{
    public ?int $id;
    public string $name;
    public ?string $email;
    public string $location;
    public ?string $phone;
    public ?Carbon $expired_at;
    public ?int $user_id;
    public ?int $kiloPrice;
    public ?string $spendingType;
    public ?string $day;
    public ?int $afterPaymentFrequency;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'user_id'=>new IntegerCast(),
            'afterPaymentFrequency'=>new IntegerCast(),
            'kiloPrice'=>new IntegerCast(),
        ];
    }
}
