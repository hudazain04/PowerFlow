<?php

namespace Database\Factories;

use App\Types\PaymentStatus;
use App\Types\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionPayment>
 */
class SubscriptionPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'amount' => $this->faker->numberBetween(1000, 50000),

            'status' => $this->faker->randomElement(
                array_values((new \ReflectionClass(PaymentStatus::class))->getConstants())
            ),
            'type' => $this->faker->randomElement(
                array_values((new \ReflectionClass(PaymentType::class))->getConstants())
            ),

            'session_id' => $this->faker->uuid(),
        ];
    }
}
