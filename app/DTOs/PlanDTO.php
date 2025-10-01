<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\CollectionCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;
use Illuminate\Support\Collection;

class PlanDTO extends SimpleDTO
{
    public ?int $id;
    public string $name;
    public string $target;
    public string $description;
    public ?string $image;
    public int $monthlyPrice;
    public ?int $popular;
    public ?Collection $features;
    public ?Collection $planPrices;


    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'id'=>new IntegerCast(),
            'monthlyPrice'=>new IntegerCast(),
            'planPrices'=>new CollectionCast(),
            'features'=>new CollectionCast(),
        ];
    }
}
