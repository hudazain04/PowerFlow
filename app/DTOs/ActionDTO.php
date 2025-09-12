<?php

namespace App\DTOs;

use Illuminate\Validation\Rules\In;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class ActionDTO extends SimpleDTO
{
    public ?int $id;
    public ?int $counter_id;
    public ?int $priority;
    public ?int $generator_id;
    public ?int $employee_id;
    public ?int $parent_id;
    public $relatedData;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'counter_id'=>new IntegerCast(),
            'parent_id'=>new IntegerCast(),
            'employee_id'=>new IntegerCast(),
            'generator_id'=>new IntegerCast(),
            'priority'=>new IntegerCast(),
        ];
    }
}
