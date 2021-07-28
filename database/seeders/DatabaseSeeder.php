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
use Illuminate\Support\Facades\Artisan;
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
       // $this->disableForeignKeyCheck();
        $this->deleteTables();
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

    public function deleteTables()
    {
        /*DB::table('users')->delete();
        DB::table('roles')->delete();
        DB::table('courses')->delete();
        DB::table('subjects')->delete();
        DB::table('monthly_schedules')->delete();
        DB::table('weekly_schedules')->delete();
        DB::table('payments')->delete();
        DB::table('video_lessons')->delete();
        DB::table('course_materials')->delete();

        DB::table('user_role')->delete();
        DB::table('user_course')->delete();
        DB::table('course_subject')->delete();
        DB::table('user_subject')->delete();
        DB::table('subject_weekly_schedule')->delete();
        DB::table('user_weekly_schedule')->delete();
        DB::table('weekly_schedule_course_material')->delete();
        DB::table('monthly_schedule_course_material')->delete();*/
        Artisan::call('db:wipe');
        Artisan::call('migrate');

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
