<?php

namespace App\Http\Requests\User;

use App\Rules\NICSizeValidate;
use App\Rules\OnlyLettersAndSpaces;
use App\Rules\UploadedFileLengthValidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name'    => [new OnlyLettersAndSpaces, 'min:6', 'max:75'],
            'n_i_c'        => [
                'numeric', new NICSizeValidate,
                Rule::unique('users', 'n_i_c')->ignore($this->user)
            ],
            'user_id'      => [
                'max:10', Rule::unique('users')->ignore($this->user)
            ],
            'address'      => ['min:5', 'max:150'],
            'nearest_town' => ['alpha', 'min:2', 'max:50'],
            'gender'       => function ($attribute, $value, $fail) {
                if ($value != 'male' and $value != 'female') {
                    $fail(':attribute must be male or female');
                }
            },
            'contact_no'   => [
                'digits-between:10,20',
                Rule::unique('users')->ignore($this->user)
            ],
            'email'        => [
                 'email', Rule::unique('users')->ignore($this->user)
            ],
            'password'     => [
                'confirmed',
                Password::min(6)->letters()->mixedCase()->numbers()->symbols()
            ],
            'image'        => ['image', 'between:0,3024', new UploadedFileLengthValidate()]
            // image size should't be exeeed 3mb

        ];
    }
}
