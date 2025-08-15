<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\SimpleDTO;

class Plan_FeatureDTO extends SimpleDTO
{
    public  ?int $id;
    public ?int $plan_id;
    public ?int $feature_id;
    public ?int $value;
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'id'=>new IntegerCast(),
            'plan_id'=>new IntegerCast(),
            'feature_id'=>new IntegerCast(),
            'value'=>new IntegerCast(),
//            'value'      => fn($v) => $v === null ? null : (int)$v,
        ];
    }

//    protected function mapData(): array
//    {
//        return [
//            'feature_id' => 'features.*.id',
//            'value'=>'features.*.value',
//        ];
//    }
}
