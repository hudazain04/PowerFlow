<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ElectricalBox>
 */
class ElectricalBoxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'location' => $this->faker->address,
            'maps' => $this->faker->url,
            'number' => $this->faker->unique()->randomNumber(5),
            'capacity'=>$this->faker->numberBetween(5,50),
        ];
    }
}
