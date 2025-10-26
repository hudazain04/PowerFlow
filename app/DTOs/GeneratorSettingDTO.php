<?php

namespace App\DTOs;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Casting\FloatCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class GeneratorSettingDTO extends SimpleDTO
{
    public ?int $id;
    public ?int $generator_id;
    public ?float $kiloPrice;
    public ?string $spendingType;
    public ?string $day;
    public ?int $afterPaymentFrequency;
    public ?Carbon $nextDueDate;


    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'generator_id'=>new IntegerCast(),
            'kiloPrice'=>new FloatCast(),
            'afterPaymentFrequency'=>new IntegerCast(),
            'nextDueDate'=>new CarbonCast(),

        ];
    }
}
