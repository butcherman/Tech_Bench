<?php

namespace Tests\Unit\Rules;

use App\Rules\ContainsLowerCase;
use Tests\TestCase;

class ContainsLowerCaseTest extends TestCase
{
    public function test_fail()
    {
        $obj = new ContainsLowerCase;

        $res = (bool) $obj->passes('password', 'HFISDFHEK');
        $this->assertFalse($res);
    }

    public function test_pass()
    {
        $obj = new ContainsLowerCase;

        $res = (bool) $obj->passes('password', 'HFIdSDFHEK');
        $this->assertTrue($res);
    }

    public function test_config_off()
    {
        config(['auth.passwords.settings.contains_lowercase' => false]);
        $obj = new ContainsLowerCase;

        $res = (bool) $obj->passes('password', 'HFISDFHEK');
        $this->assertTrue($res);
    }

    public function test_get_message()
    {
        $obj = new ContainsLowerCase;

        $res = $obj->message();
        $this->assertEquals($res, __('validation.custom.password_validation.contains_lowercase'));
    }
}
