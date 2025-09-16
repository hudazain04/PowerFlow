<?php

namespace App\Repositories\interfaces\Admin;

use App\Models\GeneratorSetting;

interface GeneratorSettingRepositoryInterface
{
    public function create(array  $data) : GeneratorSetting;

    public function update(GeneratorSetting $generatorSetting ,  array $data) : GeneratorSetting;

    public function get($generator_id) : GeneratorSetting;
}

