<?php


namespace App\Helpers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuthHelper
{
    public static function addEmailVerifiedColumn(&$data)
    {
        if (array_search('email_verified_at', $data)) {
            if ($data['email_verified_at'] === 'verified') {
                Gate::authorize('isAdministrative');

                $data['email_verified_at'] = now();
            }
        }
    }
}