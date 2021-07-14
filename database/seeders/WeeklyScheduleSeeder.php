<?php

namespace Database\Seeders;

use App\Models\CourseMaterial;
use App\Models\MonthlySchedule;
use App\Models\VideoLesson;
use App\Models\WeeklySchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WeeklyScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WeeklySchedule::factory()->count(10)->state(function (
            array $attributs
        ) {
            return [
                'end_time' => Carbon::parse($attributs['start_time'])
                    ->addHours(2),
                'monthly_schedule_id' => MonthlySchedule::all()
                    ->random()->id
            ];
            })
            ->has(VideoLesson::factory())
            ->has(CourseMaterial::factory())
            ->create();
    }
}
