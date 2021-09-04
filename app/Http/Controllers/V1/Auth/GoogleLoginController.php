<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class GoogleLoginController extends ApiController
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function register()
    {
        $user = Socialite::driver('google')->user();

        $alreadyExistUser = User::where('email', $user->email)->first();

        if ($alreadyExistUser) {
            $token = $alreadyExistUser->createToken($alreadyExistUser->email)->accessToken;

            return $this->showOne(Response::$statusTexts[Response::HTTP_OK],
                'Access granted', ['token' => $token], Response::HTTP_OK);
        }

        if ($user) {
            $password = Hash::make(ucfirst($user->email).uniqid(bin2hex(openssl_random_pseudo_bytes(5))),
                true);
            $full_name = $user->name;

           $user = User::create([
                'password' => $password,
                'email' => $user->email,
                'full_name' => $full_name,
                'user_id' => $this->generateUserId(),
            ]);
        }

        $token = $user->createToken('lakshan');

        return $this->showOne(Response::$statusTexts[Response::HTTP_CREATED],
            'Registerd successfully', ['token' => $token, 'type' => 'personal'], Response::HTTP_CREATED);
    }
}
