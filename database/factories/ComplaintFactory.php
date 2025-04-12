<?php

namespace Database\Factories;

use App\Types\ComplaintStatusTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(array_values((new \ReflectionClass(ComplaintStatusTypes::class))->getConstants())),
        ];
    }
}
