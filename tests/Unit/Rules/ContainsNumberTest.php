<?php

namespace Tests\Unit\Rules;

use App\Rules\ContainsNumber;
use Tests\TestCase;

class ContainsNumberTest extends TestCase
{
    public function test_fail()
    {
        $obj = new ContainsNumber;

        $res = (bool) $obj->passes('password', 'HFISDFHEK');
        $this->assertFalse($res);
    }

    public function test_pass()
    {
        $obj = new ContainsNumber;

        $res = (bool) $obj->passes('password', 'HFId4334SDFHEK');
        $this->assertTrue($res);
    }

    public function test_config_off()
    {
        config(['auth.passwords.settings.contains_number' => false]);
        $obj = new ContainsNumber;

        $res = (bool) $obj->passes('password', 'HFISDFHEK');
        $this->assertTrue($res);
    }

    public function test_get_message()
    {
        $obj = new ContainsNumber;

        $res = $obj->message();
        $this->assertEquals($res, __('validation.custom.password_validation.contains_number'));
    }
}
