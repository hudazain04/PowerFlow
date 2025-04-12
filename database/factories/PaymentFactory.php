<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $current = $this->faker->numberBetween(100, 1000);
        $next = $current + $this->faker->numberBetween(50, 300);
        return [
            'amount' => $current,
            'date' => $this->faker->dateTimeThisYear,
            'current_spending' => $current,
            'next_spending' => $next,
        ];
    }
}
