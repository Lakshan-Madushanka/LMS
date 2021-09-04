<?php


namespace App\Helpers;


use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserHelper
{
    public static function lastInsertedUserId()
    {
        $lastInsertedRecord = User::all()
            ->sortByDesc('id')
            ->values()
            ->first();

        return $lastInsertedRecord->id;
    }

}