<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
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
            ->has(User::factory()->count(10))
            ->has(Payment::factory());
            //->create();
    }
}
