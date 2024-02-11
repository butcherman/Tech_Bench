<?php

namespace Tests\Unit\Service;

use App\Service\TimezoneList;
use Tests\TestCase;

class TimezoneListUnitTest extends TestCase
{
    /**
     * Build Method
     */
    public function test_build()
    {
        $tzData = TimezoneList::Build();

        $this->assertArrayHasKey('General', $tzData);
        $this->assertArrayHasKey('Africa', $tzData);
        $this->assertArrayHasKey('America', $tzData);
        $this->assertArrayHasKey('Antarctica', $tzData);
        $this->assertArrayHasKey('Arctic', $tzData);
        $this->assertArrayHasKey('Asia', $tzData);
        $this->assertArrayHasKey('Atlantic', $tzData);
        $this->assertArrayHasKey('Australia', $tzData);
        $this->assertArrayHasKey('Europe', $tzData);
        $this->assertArrayHasKey('Indian', $tzData);
        $this->assertArrayHasKey('Pacific', $tzData);
    }
}
