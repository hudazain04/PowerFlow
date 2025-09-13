<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\GeneratorSetting;
use App\Repositories\interfaces\Admin\GeneratorSettingRepositoryInterface;

class GeneratorSettingRepository implements GeneratorSettingRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(array $data): GeneratorSetting
    {
        $generatorSetting=GeneratorSetting::create($data);
        return  $generatorSetting;
    }

    public function update(GeneratorSetting $generatorSetting, array $data): GeneratorSetting
    {
       $generatorSetting->update($data);
       $generatorSetting->save();
       return $generatorSetting;
    }
}
