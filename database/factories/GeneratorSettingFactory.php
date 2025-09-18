<?php

namespace Database\Factories;

use App\Types\DaysOfWeek;
use App\Types\SpendingTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GeneratorSetting>
 */
class GeneratorSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $afterPaymentFrequency = $this->faker->numberBetween(1, 4);
        $day = $this->faker->randomElement(
            array_values((new \ReflectionClass(DaysOfWeek::class))->getConstants())
        );

        return [
            'spendingType' => $this->faker->randomElement(
                array_values((new \ReflectionClass(SpendingTypes::class))->getConstants())
            ),

            'kiloPrice' => $this->faker->numberBetween(100, 1000),

            'afterPaymentFrequency' => $afterPaymentFrequency,

            'day' => $day,

            'nextDueDate' => Carbon::now()
                ->addWeeks($afterPaymentFrequency)
                ->next($day),
        ];
    }
}
