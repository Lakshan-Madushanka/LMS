<?php

namespace Database\Seeders;

use App\Models\CourseMaterial;
use App\Models\MonthlySchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MonthlyScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MonthlySchedule::factory()
            ->count(6)
            ->state(function (array $attributes) {
                    return ['end_date' =>  carbon::parse($attributes['start_date'])->addMonth(1)];
                }
            )
            ->has(CourseMaterial::factory()->count(2))
            ->create();
    }
}

