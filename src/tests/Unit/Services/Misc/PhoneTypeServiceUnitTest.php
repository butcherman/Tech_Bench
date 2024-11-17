<?php

namespace Tests\Unit\Services\Misc;

use App\Models\PhoneNumberType;
use App\Services\Misc\PhoneNumberService;
use Tests\TestCase;

class PhoneTypeServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getPhoneNumberType()
    |---------------------------------------------------------------------------
    */
    public function test_get_phone_number_type(): void
    {
        $expected = PhoneNumberType::find(1);

        $testObj = new PhoneNumberService;
        $res = $testObj->getPhoneNumberType($expected->description);

        $this->assertEquals($expected->toArray(), $res->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | cleanPhoneString()
    |---------------------------------------------------------------------------
    */
    public function test_clean_phone_string_ten_dig(): void
    {
        $testStr = '(210) 555-1212';

        $testObj = new PhoneNumberService;
        $res = $testObj->cleanPhoneString($testStr);

        $this->assertEquals('2105551212', $res);
    }

    public function test_clean_phone_string_eleven_dig(): void
    {
        $testStr = '+1 (210) 555-1212';

        $testObj = new PhoneNumberService;
        $res = $testObj->cleanPhoneString($testStr);

        $this->assertEquals('2105551212', $res);
    }

    public function test_clean_phone_string_uncommon_format(): void
    {
        $testStr = '1-210-555-1212';

        $testObj = new PhoneNumberService;
        $res = $testObj->cleanPhoneString($testStr);

        $this->assertEquals('2105551212', $res);
    }
}
