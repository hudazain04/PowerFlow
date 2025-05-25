<?php

namespace Database\Factories;

use App\Types\SubscriptionTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionRequest>
 */
class SubscriptionRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(array_values((new \ReflectionClass(SubscriptionTypes::class))->getConstants())),
            'period' => $this->faker->randomElement([1,3,6,12]),
            'location' => $this->faker->address,

        ];
    }
}
