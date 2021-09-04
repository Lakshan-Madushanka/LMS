<?php

namespace Tests\Feature\Student;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StudentFunctionalityTest extends TestCase
{
    use RefreshDatabase;


    public function test_users_cannot_access_to_student_details_without_loggin()
    {
        $response = $this->getJson('api/V1/students');
        $response->assertStatus(401);
    }

    public function test_only_logged_admin_users__can_access_to_all_student_details(
    )
    {
        $user = User::where('email_verified_at', '<>', null)
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', 'admin');
            })->first();

        $response = $this->actingAs($user, 'api')->getJson('api/V1/students');
        //dd($response->exception);
        echo $response->getContent();
        $response->assertStatus(200);
    }

    public function test_only_logged_student_type_users_can_access_to_all_student_details(
    )
    {
        $user = User::where('email_verified_at', '<>', null)
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', 'student');
            })->first();

        $response = $this->actingAs($user, 'api')->getJson('api/V1/students');
        //dd($response->exception);
        echo $response->getContent();
        $response->assertStatus(403);
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test_only_logged_admin_type_users_can_access_to_a_student_details(
    )
    {
        $authUser = User::where('email_verified_at', '<>', null)
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', 'admin');
            })->first();

        $student = Student::where('email_verified_at', '<>', null)
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', 'student');
            })->first();

        $response = $this->actingAs($authUser, 'api')
            ->getJson("api/V1/students/{$student->id}");
        //dd($response->exception);
        echo $response->getContent();
        $response->assertJson(function (AssertableJson $json) use ($student) {
                           $json
                               ->has('status')
                               ->has('status_message')
                               ->has('data', function($json) use( $student) {
                                 $json->where('n_i_c', $student->n_i_c)->etc();
                           });

                              });
        $response->assertStatus(200);
    }

    public function test_only_logged_student_users_can_access_to_only_his_student_details(
    )
    {
        $student = User::where('email_verified_at', '<>', null)
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', 'student');
            })->first();

        $student1 = Student::where('email_verified_at', '<>', null)
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', 'lecturer');
            })->first();

        $response = $this->actingAs($student, 'api')
            ->getJson("api/V1/students/{$student->id}");
        //dd($response->exception);
        echo $response->getContent();
        $response->assertJson(function (AssertableJson $json) use ($student) {
            $json
                ->has('status')
                ->has('status_message')
                ->has('data', function($json) use( $student) {
                    $json->where('n_i_c', $student->n_i_c)
                        ->etc();
                });

        });
        $response->assertStatus(200);
    }

    public function test_only_logged_student_users_canot_access_to_other_student_details(
    )
    {
        $student = User::where('email_verified_at', '<>', null)
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', 'student');
            })->first();

        // create different student
        $student1 = User::factory()->create();
        $student1->roles()->attach(4);

        $response = $this->actingAs($student, 'api')
            ->getJson("api/V1/students/{$student1->id}");

        $response->assertStatus(403);
    }

    //public function test_users_
}
