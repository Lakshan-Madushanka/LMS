<?php

namespace Database\Factories;

use App\Models\MonthlySchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthlyScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MonthlySchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'month'      => $this->faker->numberBetween(1, 12),
            'start_date' => $this->faker->dateTimeBetween('now', '6 months'),
            'payable'      => $this->faker->randomElement([true, false]),
            'fees'      => $this->faker->numberBetween(1000, 3000),
            'status'      => $this->faker->randomElement(MonthlySchedule::status),
            'description' => $this->faker->paragraph(),
        ];
    }
}
