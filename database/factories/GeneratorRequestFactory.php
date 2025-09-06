<?php

namespace Database\Factories;

use App\Types\GeneratorRequests;
use Illuminate\Database\Eloquent\Factories\Factory;
use ReflectionClass;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GeneratorRequest>
 */
class GeneratorRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'generator_name' => $this->faker->company . ' Power Generator',
            'generator_location' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement(array_values((new ReflectionClass(GeneratorRequests::class))->getConstants())),
        ];
    }
}
