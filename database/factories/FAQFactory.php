<?php

namespace Database\Factories;

use App\Types\UserTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FAQ>
 */
class FAQFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence(8),
            'answer' => $this->faker->paragraph(3),
            'role' => $this->faker->randomElement(array_values((new \ReflectionClass(UserTypes::class))->getConstants())),
        ];
    }
}
