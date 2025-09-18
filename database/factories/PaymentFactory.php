<?php

namespace Database\Factories;

use App\Types\ComplaintStatusTypes;
use App\Types\PaymentStatus;
use App\Types\PaymentType;
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
            'status' => $this->faker->randomElement(array_values((new \ReflectionClass(PaymentStatus::class))->getConstants())),
            'type' => $this->faker->randomElement(array_values((new \ReflectionClass(PaymentType::class))->getConstants())),

        ];
    }
}
