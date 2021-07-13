<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Role;
use App\Models\User;
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
           UserSeeder::class,
           CourseSeeder::class
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
        DB::table('user_role')->truncate();
        DB::table('user_course')->truncate();
    }

    public function disableEventListners()
    {
        User::flushEventListeners();
        Course::flushEventListeners();

    }
}
