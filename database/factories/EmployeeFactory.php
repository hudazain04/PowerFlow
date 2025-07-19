<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_number' => $this->faker->numerify('07########'),
            'user_name' => $this->faker->name,
//            'last_name' => $this->faker->lastName,
            'secret_key' => Str::random(10),
//            'password' => bcrypt('employee123'),
            ];
    }
}
