<?php

namespace Database\Seeders;

use App\Models\VideoLesson;
use App\Models\WeeklySchedule;
use Illuminate\Database\Seeder;

class VideoLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VideoLesson::factory()->count(10)->create();
    }
}
