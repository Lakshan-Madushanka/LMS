<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserValidationTest extends TestCase
{
    use RefreshDatabase;

   // protected $seed = true;

    public $userData
        = [
            '199028764459',
            'lamal madusha',
            'kotte, colombo',
            'colombo',
            'male',
            '0785698456',
            'lamal@gmail.com',
            'passworD@#123'
        ];

    public function loadUserData(array $user)
    {
        return [
            'n_i_c'        => $user[0],
            'full_name'    => $user[1],
            'address'      => $user[2],
            'nearest_town' => $user[3],
            'gender'       => $user[4],
            'contact_no'   => $user[5],
            'email'        => $user[6],
            'password'     => $user[7],
            'password_confirmation' => $user[7]
        ];

    }

    public function test_full_name_must_only_contain_letters_and_spaces()
    {
        $userData = $this->userData;
        $userData[1] = 'lamal# madusha123';
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);
    }

    public function test_nic_must_only_contain_numbers_and_length_12_and_9()
    {

        $userData = $this->userData;
        $userData[0] = '199028764459'; //length 12
        //dd($this->loadUserData($userData));
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(200);

        $userData = $this->userData;
        $userData[0] = '199028764'; // length 9
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(200);

        $userData = $this->userData;
        $userData[0] = '19902'; //length 5
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);

        $userData = $this->userData;
        $userData[0] = '19902abc'; //length 5 contain letters
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);
    }

   public function test_nearest_town_must_only_contain_letters()
    {
        $userData = $this->userData;
        $userData[3] = 'kaduwela#$';
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);

        $userData = $this->userData;
        $userData[3] = 'kaduwela';
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(200);
    }

    public function test_gender_must_Be_male_or_female()
    {
        $userData = $this->userData;
        $userData[4] = 'female';
        //dd($this->loadUserData($userData));
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(200);

        $userData = $this->userData;
        $userData[4] = 'female';
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(200);

        $userData = $this->userData;
        $userData[4] = 'gender';
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);
    }

   public function test_email_must_be_valid_and_unique()
    {
        $userData = $this->userData;
        $userData[6] = 'lamal'; // invalid email
        //dd($this->loadUserData($userData));
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);

        $userData[6] = 'lamal@gmail.com'; // valid email
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(200);

       /* $userData[6] = 'lamal@gmail.com'; // entered already exists email
        $response1 = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response1->assertStatus(422);*/
    }

    public function test_password_is_valid()
    {
        $userData = $this->userData;
        $userData[7] = 'passw'; // length less than 7
        //dd($this->loadUserData($userData))5
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);

        $userData[7] = 'password'; // number not included
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);

        $userData[7] = 'password@'; // miss case not included
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);

        $userData[7] = 'passworD'; // symbol not included
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(422);

        $userData[7] = 'passworD$%98'; // should be valid
        $response = $this->postJson('api/v1/students', $this->loadUserData($userData));
        //dd($response->exception);
        echo $response->content();
        $response->assertStatus(200);
    }

    public function test_user_image_type_must_be_a_valid_mime_type()
    {
        $userData = $this->userData;
        $userData['image']  = UploadedFile::fake()->image('user1.jpg'); //mime type is ok
        $response = $this->postJson('api/v1/users', $this->loadUserData($userData));
        echo $response->getContent();
        echo $response->exception;
        $response->assertStatus(201);
    }

    public function test_user_image_type_must_not_be_a_invalid_mime_type()
    {
        $userData = $this->userData;
        $userData['image']  = UploadedFile::fake()->image('user1.mp3'); //mime type is ok
        $response = $this->postJson('api/v1/users', $this->loadUserData($userData));
        echo $response->getContent();
        echo $response->exception;
        $response->assertStatus(422);
    }

}




/*n_i_c,
full_name,
address,
nearest_town,
gender,
contact_no,
email,
password*/



