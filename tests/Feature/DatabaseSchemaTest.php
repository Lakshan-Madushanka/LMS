<?php

namespace Tests\Feature;

use App\Models\MonthlySchedule;
use App\Models\User;
use App\Models\VideoLesson;
use Database\Seeders\CourseSeeder;
use Database\Seeders\MonthlyScheduleSeeder;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VideoLessonSeeder;
use Database\Seeders\WeeklyScheduleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Tinker\Console\TinkerCommand;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /* public function test_example()
     {
         $response = $this->get('/');

         $response->assertStatus(200);
     }*/

    public function test_database_schema_is_created_successfully()
    {
        // DB::statement("SHOW TABLES");

    }

    /* public function test_subjects_can_be_created()
     {
         $this->seed(UserSeeder::class);
         $this->assertDatabaseCount('users', 4);

     }*/

    public function test_users_can_be_created_and_assign_roles_and_subjects()
    {
        $this->seed(MonthlyScheduleSeeder::class);
        $this->seed(UserSeeder::class);

        $this->assertDatabaseCount('users', 4);
        $this->assertDatabaseCount('subjects', 12);

    }

    public function test_roles_can_be_created()
    {
        $this->seed(UserSeeder::class);
        $this->assertDatabaseCount('roles', 4);
    }

    public function test_monthly_schedules_can_be_created()
    {
        $this->seed(MonthlyScheduleSeeder::class);
        $this->assertDatabaseCount('monthly_schedules', 6);
    }

    public function test_courses_can_be_created_with_subjects()
    {
        $this->seed(MonthlyScheduleSeeder::class);
        $this->seed(CourseSeeder::class);
        $this->assertDatabaseCount('courses', 5);
        $this->assertDatabaseCount('subjects', 10);

    }

    public function test_payments_can_be_created_for_a_course()
    {
        $this->seed(MonthlyScheduleSeeder::class);
        $this->seed(CourseSeeder::class);
        $this->assertDatabaseCount('payments', 5);
    }

    public function test_subjects_can_be_created_and_assign_weekly_schedule()
    {
        $this->seed(MonthlyScheduleSeeder::class);
        $this->seed(SubjectSeeder::class);
        $this->assertDatabaseCount('subjects', 15);
        $this->assertDatabaseCount('weekly_schedules', 15);
    }

    public function test_monthly_schedule_can_be_created_and_assign_course_materials(
    )
    {
        $this->seed(MonthlyScheduleSeeder::class);
        $this->assertDatabaseCount('course_materials', 12);
        $this->assertDatabaseCount('monthly_schedules', 6);
    }

    public function test_weekly_schedule_can_be_created_and_assign_to_a_video_lessons_and_course_materials()
    {
        $this->seed(MonthlyScheduleSeeder::class);
        $this->seed(WeeklyScheduleSeeder::class);
        $this->assertDatabaseCount('video_lessons', 10);
        $this->assertDatabaseCount('weekly_schedules', 10);
        $this->assertDatabaseCount('course_materials', 22);
    }
}
