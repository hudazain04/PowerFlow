<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feature>
 */
class FeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->randomElement([
                'users_count',
                'counters_count',
                'neighborhoods_count',
                'boxes_count',
                'areas_count',
                'employee_count',
                'areas_count'
            ]),
            'description' => $this->faker->sentence,

        ];
    }
}
