<?php

namespace App\Actions\Fortify;

use App\Rules\ContainsLowerCase;
use App\Rules\ContainsNumber;
use App\Rules\ContainsSpecialChar;
use App\Rules\ContainsUpperCase;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     */
    protected function passwordRules(): array
    {
        $minLength = config('auth.passwords.settings.disable_compromised')
            ? Password::min(config('auth.passwords.settings.min_length'))
                ->uncompromised(3)
            : Password::min(config('auth.passwords.settings.min_length'));

        return [
            'required',
            'string',
            'confirmed',
            'different:current_password',
            new ContainsUpperCase,
            new ContainsLowerCase,
            new ContainsNumber,
            new ContainsSpecialChar,
            $minLength,
        ];
    }

    /**
     * During initial setup, we set some temporary rules to allow the initial
     * wizard to continue
     */
    protected function tmpPasswordRules(array $passRules): array
    {
        $basePath = 'auth.passwords.settings.';

        config([
            $basePath.'.disable_compromised' => $passRules['disable_compromised'],
            $basePath.'.min_length' => $passRules['min_length'],
            $basePath.'.contains_uppercase' => $passRules['contains_uppercase'],
            $basePath.'.contains_lowercase' => $passRules['contains_lowercase'],
            $basePath.'.contains_number' => $passRules['contains_number'],
            $basePath.'.contains_special' => $passRules['contains_special'],
        ]);

        return $this->passwordRules();
    }
}
