<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use PhpParser\Node\Stmt\Global_;
use Psy\Util\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        User::factory()->count(4)
            ->has(Course::factory())
            ->has(Role::factory()->sequence(
                function ($sequence) {
                    return ['name' => Role::roles[$sequence->index]];
                }
            )
            )
            ->has(Subject::factory()->count(3))
            ->create();

        User::factory()->count(20)->create()->each(function ($user, $index) {
            $user->roles()->attach(array_search(Role::roles[$index
                % count(Role::roles)], Role::roles) + 1);
        });

    }

}
