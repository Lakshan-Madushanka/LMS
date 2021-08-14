<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NICSizeValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $nicLength = strlen((String)$value);
        return $nicLength === 12 or $nicLength === 9;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must be 12 or 9 in length.';
    }
}
