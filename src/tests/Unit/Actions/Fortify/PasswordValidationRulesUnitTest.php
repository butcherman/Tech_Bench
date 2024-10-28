<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\PasswordValidationRules;
use App\Rules\ContainsLowerCase;
use App\Rules\ContainsNumber;
use App\Rules\ContainsSpecialChar;
use App\Rules\ContainsUpperCase;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class PasswordValidationRulesUnitTest extends TestCase
{
    use PasswordValidationRules;

    /*
    |---------------------------------------------------------------------------
    | Primary Password Rules
    |---------------------------------------------------------------------------
    */
    public function test_password_rules_default()
    {
        $rules = $this->passwordRules();
        $shouldBe = [
            'required',
            'string',
            'confirmed',
            'different:current_password',
            new ContainsUpperCase,
            new ContainsLowerCase,
            new ContainsNumber,
            new ContainsSpecialChar,
            Password::min(6),
        ];

        $this->assertEquals($shouldBe, $rules);
    }

    public function test_password_rules_no_compromised()
    {
        config(['auth.passwords.settings.disable_compromised' => true]);

        $rules = $this->passwordRules();
        $shouldBe = [
            'required',
            'string',
            'confirmed',
            'different:current_password',
            new ContainsUpperCase,
            new ContainsLowerCase,
            new ContainsNumber,
            new ContainsSpecialChar,
            Password::min(6)->uncompromised(3),
        ];

        $this->assertEquals($shouldBe, $rules);
    }

    /*
    |---------------------------------------------------------------------------
    | Password Rules used during Initial Setup
    |---------------------------------------------------------------------------
    */
    public function test_tmp_password_rules_default()
    {
        $passRules = [
            'disable_compromised' => false,
            'min_length' => 6,
            'contains_uppercase' => true,
            'contains_lowercase' => true,
            'contains_number' => true,
            'contains_special' => true,
        ];

        $rules = $this->tmpPasswordRules($passRules);
        $shouldBe = [
            'required',
            'string',
            'confirmed',
            'different:current_password',
            new ContainsUpperCase,
            new ContainsLowerCase,
            new ContainsNumber,
            new ContainsSpecialChar,
            Password::min(6),
        ];

        $this->assertEquals($shouldBe, $rules);
    }
}
