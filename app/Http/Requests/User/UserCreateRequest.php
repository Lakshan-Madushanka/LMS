<?php

namespace App\Http\Requests\User;

use App\Rules\NICSizeValidate;
use App\Rules\OnlyLettersAndSpaces;
use App\Rules\SpecialCharactersNotAllowed;
use App\Rules\UploadedFileLengthValidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => [
                'required', new OnlyLettersAndSpaces, 'min:6', 'max:75'
            ],
            'n_i_c' => ['numeric', new NICSizeValidate],
            'user_id' => ['max:10', 'unique:users,user_id'],
            'address' => ['min:5', 'max:150'],
            'nearest_town' => ['alpha', 'min:2', 'max:50'],
            'gender' => function ($attribute, $value, $fail) {
                if ($value != 'male' and $value != 'female') {
                    $fail(':attribute must be male or female');
                }
            },
            'contact_no' => [
                'digits-between:10,20', 'unique:users,contact_no'
            ],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'confirmed',
                Password::defaults()
            ],
            'image' => [
                'image', 'between:0,3024', new UploadedFileLengthValidate()
            ] // image size should't be exeeed 3mb

        ];
    }
}
