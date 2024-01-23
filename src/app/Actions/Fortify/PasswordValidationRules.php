<?php

namespace App\Actions\Fortify;

use App\Rules\ContainsLowerCase;
use App\Rules\ContainsNumber;
use App\Rules\ContainsSpecialChar;
use App\Rules\ContainsUpperCase;
use Illuminate\Validation\Rules\Password;

/**
 * Get the validation rules used to validate passwords
 */
trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            'confirmed',
            // 'different:current_password',
            new ContainsUpperCase,
            new ContainsLowerCase,
            new ContainsNumber,
            new ContainsSpecialChar,
            // Password::min(6)->uncompromised(3),
        ];
    }
}
