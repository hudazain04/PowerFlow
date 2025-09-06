<?php

namespace Database\Factories;

use App\Models\ElectricalBox;
use App\Models\PowerGenerator;
use App\Models\User;
use App\Types\GeneratorRequests;
use App\Types\SpendingTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerRequest>
 */
class CustomerRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(
                array_values((new \ReflectionClass(GeneratorRequests::class))->getConstants())
            ),
            'spendingType' => $this->faker->randomElement(
                array_values((new \ReflectionClass(SpendingTypes::class))->getConstants())
            ),

            'user_notes' => $this->faker->optional()->sentence,
            'admin_notes' => $this->faker->optional()->sentence,

        ];
    }
}
