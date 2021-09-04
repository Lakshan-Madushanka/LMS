<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function test_user_can_be_added_to_the_system()
    {
        $user = $this->addUser();
        //removing email verified column
        unset($user['email_verified_at']);
        $response = $this->actingAs(User::all()->first(), 'api')
            ->postJson('api/V1/users', $user);

        $userCount = User::where('full_name', '=', 'kashan kumara')->get()
            ->count();

        //  echo $response->exception;
        //  dd($response->exception);
        echo $response->getContent();
        $this->assertEquals(1, $userCount);
        $response->assertStatus(201);
    }

    /*
     *
     test user detailes can be updated
    *
     */
    /* public function test_user_cannot_update_email_verified_column_without_being_admin(
     )
     {
         $user = $this->addUser(); // contain email verified column

         $response1 = $this->actingAs(User::all()->first(), 'api')
             ->postJson('api/V1/users', $user);

         $user2 = User::latest()->limit(1)->first();
         $userId = $user2->id;

         $response2 = $this->actingAs($user2, 'api')
             ->putJson("api/V1/users/{$userId}", $user);

         // echo $response2->exception;
         echo $response1->getContent();
         $response1->assertStatus(403);
         $response2->assertStatus(403);

     }*/

    /*public function test_only_admin_users_can_update_email_verified_column()
    {
        //contain email verified column
        $adminUser = User::whereHas('roles', function (Builder $query) {
            $query->where('name', 'admin');
        })->first();

        $userId = User::latest()->limit(1)->first()->id;

        $response2 = $this->actingAs($adminUser, 'api')
            ->patchJson("api/V1/users/{$userId}", $this->getUser());

        //echo $response2->exception;
        //echo $response1->getContent();
        //$response1->assertStatus(201);
        $response2->assertStatus(422);

    }*/

    public function test_user_can_be_deleted()
    {
       $user = User::all()->random()->first();

       //echo request()->fullUrl();
       //dd();

        $response = $this->actingAs($user, 'api')->deleteJson("/api/V1/users/{$user->id}");

        echo $response->exception;
        $response->assertStatus(200);
    }

    //////////////////////helper functions//////////////////////////////
    public function addUser()
    {
        $image = UploadedFile::fake()->image('user.jpg');
        $user = User::factory()->make([
            'nearest_town' => 'colombo',
            'full_name' => 'kashan kumara',
            'n_i_c' => '123456789',
        ]);
        $user = $user->toArray();
        $user['password'] = 'Lakshgt$%22';
        $user['password_confirmation'] = $user['password'];
        $user['image'] = UploadedFile::fake()->image('user.png');

        return $user;
    }

    public function getUser()
    {
        $user = User::all()->random()->first()->toArray();
        $user['image'] = UploadedFile::fake()->image('user.jpg');
        $user['full_name'] = 'ranuja ranalka';
        $user['nearest_town'] = 'rathmalana';
        $user['password'] = 'Lakshgt$%22';
        $user['password_confirmation'] = $user['password'];

        return $user;
    }
}
