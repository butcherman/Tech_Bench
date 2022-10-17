<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ContainsLowerCase implements Rule
{
   /**
     * Determine if the validation rule passes
     */
    public function passes($attribute, $value)
    {
        //  The configuration allows for this rule to be skipped
        if(!config('auth.passwords.settings.contains_lowercase'))
        {
            return true;
        }

        return preg_match('/[a-z]/', $value);
    }

    /**
     * Get the validation error message
     */
    public function message()
    {
        return __('validation.custom.password_validation.contains_lowercase');
    }
}
