<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\ApiController;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailController extends ApiController
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    public function verify($id, Request $request)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return $this->isEmailVerified($request, $user);
        }

        if (!hash_equals((string) $request->route('id'),
            (string) $user->getKey())
        ) {
            throw new AccessDeniedException('Invalid User');
        }

        if (!$request->hasValidSignature()) {
            return $this->showError(
                Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                'Invalid or Expired URL provided.', null,
                Response::HTTP_BAD_REQUEST
            );
        }

        if (hash_equals((string) $request->route('hash'), sha1($user
            ->getEmailForVerification()))
        ) {
            $user->markEmailAsVerified();

            return $this->showOne(Response::$statusTexts[Response::HTTP_OK],
                'Email verified',
                Response::HTTP_OK);
        }

    }

    public function resend($id, Request $request)
    {

        $user = User::findOrFail($id);

        return $this->isEmailVerified($request, $user);

        $user->sendEmailVerificationNotification();

        return $this->showOne(
            Response::$statusTexts[Response::HTTP_OK],
            'Email verification link sent to your email',
            null,
            Response::HTTP_OK
        );
    }

    protected function isEmailVerified(Request $request, $user)
    {
        if ($user
            ->hasVerifiedEmail()
        ) {
            return $this->showError(
                Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                'Email has been already verified.',
                Response::HTTP_BAD_REQUEST

            );
        }
    }
}
