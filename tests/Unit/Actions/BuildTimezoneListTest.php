<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildTimezoneList;
use Tests\TestCase;

class BuildTimezoneListTest extends TestCase
{
    /**
     * Build Method
     */
    public function test_build()
    {
        $tzData = (new BuildTimezoneList)->build();

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
