<?php

namespace Database\Factories;

use App\Models\WeeklySchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeeklyScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WeeklySchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_time' => $this->faker->dateTimeBetween('now', '3 months'),
            'payable'      => $this->faker->randomElement([true, false]),
            'status'      => $this->faker->randomElement(WeeklySchedule::status),
            'description' => $this->faker->paragraph(),
        ];
    }
}
