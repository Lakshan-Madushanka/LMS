<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'        => $this->faker->unique()->word(),
            'start_date'  => $this->faker->dateTimeBetween('now', '3 months'),
            'end_date'    => $this->faker->dateTimeBetween('6 months',
                '1 years'),
            'payable'     => $this->faker->randomElement([true, false]),
            'fees'        => $this->faker->numberBetween(10000, 100000),
            'status'      => $this->faker->randomElement(Course::status),
            'description' => $this->faker->paragraph(),
        ];
    }
}
