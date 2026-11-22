<?php

namespace Tests\Unit\Actions\Misc;

use App\Actions\Misc\BuildTimezoneList;
use Tests\TestCase;

class BuildTimezoneListUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | build()
    |---------------------------------------------------------------------------
    */
    public function test_build(): void
    {
        $testObj = new BuildTimezoneList;
        $res = $testObj->build();

        $this->assertIsArray($res);
        $this->assertEquals($res[0]['label'], 'General');
        $this->assertEquals($res[1]['label'], 'America');
        $this->assertIsArray($res[0]['items']);
        $this->assertIsArray($res[1]['items']);
    }
}
