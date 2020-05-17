<?php

namespace Tests\Unit\TechTips;

use Tests\TestCase;

use App\Domains\TechTips\GetTechTipComments;

use App\TechTips;
use App\TechTipComments;

class GetTectTipCommentsTest extends TestCase
{
    protected $testTip, $testComments, $testUser, $testObj;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testTip      = factory(TechTips::class)->create();
        $this->testComments = factory(TechTipComments::class, 5)->create([
            'tip_id' => $this->testTip->tip_id,
        ]);
        $this->testObj      = new GetTechTipComments;
    }

    //  Test getting the comments - none belong to the user
    public function test_execute()
    {
        $comments = $this->actingAs($this->getTech())->testObj->execute($this->testTip->tip_id)->makeHidden('user');
        $this->assertEquals($comments->toArray(), $this->testComments->toArray());
    }

    public function test_execute_with_my_comment()
    {
        $newComments = factory(TechTipComments::class)->create([
            'user_id' => $testUser = $this->getTech(),
        ]);

        $comments = $this->actingAs($testUser)->testObj->execute($newComments->tip_id)->first();
        $this->assertTrue($comments->edit);
    }
}
