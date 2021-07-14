<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\MonthlySchedule;
use App\Models\Payment;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::factory()->count(5)
            ->has(
                Subject::factory()->count(2)
                ->state([
                    'monthly_schedule_id' => MonthlySchedule::all()->random()->id
                    ]
                )
            )
            ->has(
                Payment::factory()->state([
                    'monthly_schedule_id' => MonthlySchedule::all()->random()->id
                ])
            )
            ->create();
    }
}
