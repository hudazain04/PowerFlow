<?php

namespace Database\Factories;

use App\Types\AppInfoTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use ReflectionClass;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppInfo>
 */
class AppInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = array_values((new ReflectionClass(AppInfoTypes::class))->getConstants());
        return [
            'type' => $this->faker->randomElement($types),
            'content' => $this->faker->paragraph(3),
        ];
    }
}
