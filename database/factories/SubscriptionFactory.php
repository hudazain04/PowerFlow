<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_time' => $this->faker->dateTimeThisYear,
            'period' => $this->faker->randomElement([30, 60, 90]),
            'price' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
