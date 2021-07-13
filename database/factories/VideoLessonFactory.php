<?php

namespace Database\Factories;

use App\Models\VideoLesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoLessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VideoLesson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url' => $this->faker->url()
        ];
    }
}
