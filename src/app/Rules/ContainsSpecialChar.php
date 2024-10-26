<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContainsSpecialChar implements ValidationRule
{
    /**
     * Should the password be required to contain a Special Character (#, @, !)?
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        //  The configuration allows for this rule to be skipped
        if (config('auth.passwords.settings.contains_uppercase')) {
            if (! preg_match('/[@$!%*#?&]/', $value)) {
                $fail('The :attribute must contain a Special Character');
            }
        }
    }
}
