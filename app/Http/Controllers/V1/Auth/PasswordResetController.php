<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetController extends ApiController
{
    public function forgot(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email']]);

        Password::sendResetLink($credentials);

        return $this->showOne(Response::$statusTexts[Response::HTTP_OK],
            'Reset link sent to your email', null, Response::HTTP_OK);
    }

    public function reset(Request $request)
    {
        $queryToken = $request->query('token');

        if (!empty($queryToken)) {
            return $this->showError(Response::$statusTexts[Response::HTTP_OK],
                'Redirect to password reset page', null, Response::HTTP_OK);
        }
        $credentials = request()->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => [\Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $reset_password_status = Password::reset($credentials,
            function ($user, $password) {
                $user->password = $password;
                $user->save();
            });

        throw_if($reset_password_status == Password::INVALID_TOKEN,
            ValidationException::withMessages(['token' => 'Invalid token']));
    }
}
