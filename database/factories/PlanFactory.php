<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'target' => $this->faker->word,
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->sentence,
            'monthlyPrice'=>$this->faker->numberBetween(5,50),
        ];
    }
}
