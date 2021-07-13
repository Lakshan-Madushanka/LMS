<?php

namespace Database\Seeders;

use App\Models\Role;
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
        ->has(Role::factory()->sequence(
            function ($sequence) {
                return ['name' => Role::roles[$sequence->index]];
            }
        )
        )->create();
    }
}
