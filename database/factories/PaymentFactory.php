<?php

namespace Database\Factories;

use App\Models\payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payment_id' => Str::uuid(),
            'amount' => $this->faker->numberBetween(1000, 100000),
            'approval' => $this->faker->randomElement([true, false]),
            'date' => $this->faker->dateTimeBetween(now, '4 months')

        ];
    }
}
