<?php

namespace Database\Seeders;

use App\Models\AppInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ReflectionClass;

class AppInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appInfos = array_values((new ReflectionClass(\App\Types\AppInfoTypes::class))->getConstants());

        foreach ($appInfos as $info) {
            AppInfo::firstOrCreate([
                'type' => $info,
                'content'=>null
            ]);
        }
    }
}
