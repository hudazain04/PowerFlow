<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\FloatCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class ElectricalBoxDTO extends SimpleDTO
{
    public  ?int $generator_id;
    public  ?string $number;
    public  ?string $location;
    public  ?int $capacity;
    public ?float $latitude;
    public ?float $longitude;

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'generator_id'=>new IntegerCast(),
            'capacity'    => new IntegerCast(),
            'latitude'    => new FloatCast(),
            'longitude'   => new FloatCast(),
        ];
    }
    protected function mapData(): array
    {
        return [
            'latitude'  => 'maps.x', // maps.x → $latitude
            'longitude' => 'maps.y', // maps.y → $longitude
        ];
    }
}
