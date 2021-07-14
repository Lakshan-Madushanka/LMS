<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\MonthlySchedule;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use App\Models\VideoLesson;
use App\Models\WeeklySchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeyCheck();
        $this->truncateTables();
        $this->disableEventListners();

       $this->call([
           MonthlyScheduleSeeder::class,
           WeeklyScheduleSeeder::class,
           UserSeeder::class,
           CourseSeeder::class,
           SubjectSeeder::class
       ]);
    }

    public function disableForeignKeyCheck()
    {
        switch (DB::getDriverName()) {
            case 'mysql' :
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                break;
            case 'sqlite' :
                DB::statement('PRAGMA FOREIGN_KEYS=OFF');
        }
    }

    public function truncateTables()
    {
        User::truncate();
        Role::truncate();
        Course::truncate();
        Subject::truncate();
        MonthlySchedule::truncate();
        WeeklySchedule::truncate();
        Payment::truncate();
        VideoLesson::truncate();
        CourseMaterial::truncate();

        DB::table('user_role')->truncate();
        DB::table('user_course')->truncate();
        DB::table('course_subject')->truncate();
        DB::table('user_subject')->truncate();
        DB::table('subject_weekly_schedule')->truncate();
        DB::table('user_weekly_schedule')->truncate();
        DB::table('weekly_schedule_course_material')->truncate();
        DB::table('monthlyly_schedule_course_material')->truncate();

    }

    public function disableEventListners()
    {
        User::flushEventListeners();
        Course::flushEventListeners();
        Subject::flushEventListeners();
        Role::flushEventListeners();
        MonthlySchedule::flushEventListeners();
        WeeklySchedule::flushEventListeners();
        Payment::flushEventListeners();
        VideoLesson::flushEventListeners();
        CourseMaterial::flushEventListeners();

    }
}
