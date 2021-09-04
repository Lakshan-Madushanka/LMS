<?php


namespace App\Helpers;


use Illuminate\Http\Request;

class RequestHelper
{
    public static function isContainRoles()
    {
        return \request()->has('roles') && !empty(\request()->roles);
    }
}