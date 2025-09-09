<?php

namespace Database\Factories;

use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Action>
 */
class ActionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actionTypes = array_values((new \ReflectionClass(ActionTypes::class))->getConstants());
        $statusTypes = array_values((new \ReflectionClass(ComplaintStatusTypes::class))->getConstants());

        return [
            'type' => $this->faker->randomElement($actionTypes),
            'status' => $this->faker->randomElement($statusTypes),
        ];

    }
}
