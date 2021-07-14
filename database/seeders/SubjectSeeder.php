<?php

namespace Database\Seeders;

use App\Models\MonthlySchedule;
use App\Models\Subject;
use App\Models\WeeklySchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subject::factory()->count(15)
            ->has(
                WeeklySchedule::factory()->state(function (
                    array $attributs
                ) {
                    return [
                        'end_time' => Carbon::parse($attributs['start_time'])
                            ->addHours(2),
                        'monthly_schedule_id' => MonthlySchedule::all()
                            ->random()->id
                    ];
                })
            )
            ->create();
    }
}
