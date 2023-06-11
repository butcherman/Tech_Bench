<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContainsLowerCase implements ValidationRule
{
    /**
     * Run the validation rule
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //  The configuration allows for this rule to be skipped
        if (config('auth.passwords.settings.contains_lowercase')) {
            if(!preg_match('/[a-z]/', $value)) {
                $fail('The :attribute must contain a Lower Case Letter');
            }
        }
    }
}
