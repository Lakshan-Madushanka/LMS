<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        return [
            'n_i_c'                 => $this->faker->randomNumber(8)
                .$this->faker->randomNumber(1),
            'user_id'               => Str::random(5),
            'full_name'             => $this->faker->firstName(),
            'address'               => $this->faker->address(),
            'nearest_town'          => $this->faker->city(),
            'gender'                => $this->faker->randomElement([
                'male', 'female'
            ]),
            'contact_no'            => $this->faker->randomNumber(8)
                .$this->faker->randomNumber(4),
            //$this->faker->unique()->phoneNumber,
            'description'           => $this->faker->paragraph(2),
            'email'                 => $this->faker->unique()->safeEmail(),
            'email_verified_at'     => now(),
            'password'              => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token'        => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
