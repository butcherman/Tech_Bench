<?php

namespace Tests\Unit\Rules;

use App\Rules\ContainsSpecialChar;
use Tests\TestCase;

class ContainsSpecialCharTest extends TestCase
{
    public function test_fail()
    {
        $obj = new ContainsSpecialChar;

        $res = (bool) $obj->passes('password', 'HFISDFHEK');
        $this->assertFalse($res);
    }

    public function test_pass()
    {
        $obj = new ContainsSpecialChar;

        $res = (bool) $obj->passes('password', 'HFIdS@#DFHEK');
        $this->assertTrue($res);
    }

    public function test_config_off()
    {
        config(['auth.passwords.settings.contains_special' => false]);
        $obj = new ContainsSpecialChar;

        $res = (bool) $obj->passes('password', 'HFISDFHEK');
        $this->assertTrue($res);
    }

    public function test_get_message()
    {
        $obj = new ContainsSpecialChar;

        $res = $obj->message();
        $this->assertEquals($res, __('validation.custom.password_validation.contains_special'));
    }
}
