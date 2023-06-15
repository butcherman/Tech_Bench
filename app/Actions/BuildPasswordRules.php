<?php

namespace App\Actions;

use Illuminate\Support\Facades\Cache;

class BuildPasswordRules
{
    public function build()
    {
        $passwordRules = [
            'Password must be at least '.
            config('auth.passwords.settings.min_length').
            ' characters'];

        if (config('auth.passwords.settings.contains_uppercase')) {
            $passwordRules[] = 'Must contain an Uppercase letter';
        }

        if (config('auth.passwords.settings.contains_lowercase')) {
            $passwordRules[] = 'Must contain a Lowercase letter';
        }

        if (config('auth.passwords.settings.contains_number')) {
            $passwordRules[] = 'Must contain a Number';
        }

        if (config('auth.passwords.settings.contains_special')) {
            $passwordRules[] = 'Must contain a Special Character';
        }

        Cache::put('passwordRules', $passwordRules);

        return $passwordRules;
    }
}
