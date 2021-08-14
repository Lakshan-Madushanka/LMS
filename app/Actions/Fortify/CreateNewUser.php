<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     *
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            //'name' => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => [
                'confirmed',
                Password::min(6)->letters()->mixedCase()->numbers()->symbols()
            ],
        ])->validate();

        $currentUserID = User::all()->count() ? User::all()->sortByDesc('id')
            ->values()->first()->id + 1 : 1;

        return User::create([
            'user_id'  => '1a101',//"now()->year_{$currentUserID}",
            'full_name' => $input['full_name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
