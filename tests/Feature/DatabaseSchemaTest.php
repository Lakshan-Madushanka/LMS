<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CourseSeeder;
use Database\Seeders\UserSeeder;
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

   /*public function test_database_schema_is_created_successfully()
   {
      // DB::statement("SHOW TABLES");

   }*/

   public function test_users_can_be_created()
   {
       $this->seed(UserSeeder::class);
       $this->assertDatabaseCount('users', 4);

   }

    public function test_roles_can_be_created()
    {
        $this->seed(UserSeeder::class);
        $this->assertDatabaseCount('roles', 4);

    }

    public function test_courses_can_be_created()
    {
        $this->seed(CourseSeeder::class);
        $this->assertDatabaseCount('courses', 5);

    }
}
