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
        // @codeCoverageIgnoreStart
        $minLength = config('auth.passwords.settings.disable_compromised') ?
            Password::min(config('auth.passwords.settings.min_length'))
                ->uncompromised(3) :
            Password::min(config('auth.passwords.settings.min_length'));
        // @codeCoverageIgnoreEnd

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

    protected function tmpPasswordRules(array $passRules): array
    {
        config([
            'auth.passwords.settings.disable_compromised' => $passRules['disable_compromised'],
            'auth.passwords.settings.min_length' => $passRules['min_length'],
            'auth.passwords.settings.contains_uppercase' => $passRules['contains_uppercase'],
            'auth.passwords.settings.contains_lowercase' => $passRules['contains_lowercase'],
            'auth.passwords.settings.contains_number' => $passRules['contains_number'],
            'auth.passwords.settings.contains_special' => $passRules['contains_special'],
        ]);

        return $this->passwordRules();
    }
}
