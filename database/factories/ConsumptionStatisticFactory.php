<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsumptionStatistic>
 */
class ConsumptionStatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $q1 = $this->faker->randomFloat(2, 10, 50);
        $q3 = $this->faker->randomFloat(2, 60, 120);
        $iqr = $q3 - $q1;
        $upperBound = $q3 + (1.5 * $iqr);

        return [
            'q1'           => $q1,
            'q3'           => $q3,
            'iqr'          => $iqr,
            'upper_bound'  => $upperBound,
        ];
    }
}
