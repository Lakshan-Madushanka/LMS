<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /* public function test_user_can_not_login_to_the_system_using_fake_credentials()
     {
         //login using fake credentials
         $response = $this->postJson('/api/v1/login',[
              'emai' => 'fakemail.co',
              'password' => 'password'
          ], [
                 'accept' => 'application/json'
             ]
         );
          //echo $response->exception;
          $response->getContent();
          $response->assertStatus(422);

         $response = $this->postJson('/api/v1/login', [
             'email'    => Student::all()->random()->email,
             'password' => 'password'
         ], [
             'accept' => 'application/json'
         ]
         );
         //echo $response->exception;
         echo $response->getContent();
         $response->assertStatus(200);
     }*/

    /* public function test_user_can_not_login_to_the_system_using_valid_credentials()
     {
         //login using valid credentials
         $response = $this->postJson('/api/v1/login', [
             'email'    => Student::all()->random()->email,
             'password' => 'password'
         ], [
                 'accept' => 'application/json'
             ]
         );
         //echo $response->exception;
         echo $response->getContent();
         $response->assertStatus(200);
     }

     public function test_user_can_not_register_to_the_system()
     {
         //login using valid credentials
         $response = $this->postJson('/api/v1/register', [
             'full_name' => 'siril',
             'email'    => 'mals@gmail.com',
             'password' => 'password*L1',
             'password_confirmation' => 'password*L1'

         ], [
                 'accept' => 'application/json'
             ]
         );
         //dd( $response->exception);
         echo $response->getContent();
         $response->assertStatus(201);
     }*/

    public function test_users_cannot_access_to_student_details_without_loggin()
    {
        $response = $this->getJson('api/v1/students');
        $response->assertStatus(401);
    }

    public function test_only_logged_admin_users__can_access_to_student_details()
    {
        $user = User::where('email_verified_at', '<>', null)
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', 'admin');
            })->first();

        $response = $this->actingAs($user, 'api')->getJson('api/v1/students');
        //dd($response->exception);
        echo $response->getContent();
        $response->assertStatus(200);
    }
}
