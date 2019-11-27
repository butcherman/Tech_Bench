<?php

namespace Tests\Feature\TechTips;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TechTipsIndexTest extends TestCase
{
    protected $tips;

    // public function setUp():void
    // {
    //     Parent::setup();

    //     $this->tips = factory(TechTips::class, 25)->create();
    //     foreach($this->tips as $tip)
    //     {
    //         factory(App\TechTipSystems::class)->create([
    //             'sys_id' => $tip->tip_id,
    //         ]);
    //     }
    // }

    public function testSomethign()
    {
        $this->assertTrue(true);
    }
}
