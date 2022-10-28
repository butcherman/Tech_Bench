<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\ContainsUpperCase;

class ContainsUpperCaseTest extends TestCase
{
    public function test_fail()
    {
        $obj = new ContainsUpperCase;

        $res = (bool) $obj->passes('password', 'fdahisef');
        $this->assertFalse($res);
    }

    public function test_pass()
    {
        $obj = new ContainsUpperCase;

        $res = (bool) $obj->passes('password', 'fdahDSisef');
        $this->assertTrue($res);
    }

    public function test_config_off()
    {
        config(['auth.passwords.settings.contains_uppercase' => false]);
        $obj = new ContainsUpperCase;

        $res = (bool) $obj->passes('password', 'fdahisef');
        $this->assertTrue($res);
    }

    public function test_get_message()
    {
        $obj = new ContainsUpperCase;

        $res = $obj->message();
        $this->assertEquals($res, __('validation.custom.password_validation.contains_uppercase'));
    }
}
