<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContainsNumber implements ValidationRule
{
    /**
     * Should the password be required to contain a Number?
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        //  The configuration allows for this rule to be skipped
        if (config('auth.passwords.settings.contains_number')) {
            if (! preg_match('/[0-9]/', $value)) {
                $fail('The :attribute must contain a Number');
            }
        }
    }
}
