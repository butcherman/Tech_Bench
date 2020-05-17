<?php

namespace Tests\Unit\TechTips;

use Carbon\Carbon;
use Tests\TestCase;

use Illuminate\Support\Facades\DB;

use App\TechTips;

use App\Domains\TechTips\GetTechTipStats;

class GetTechTipStatsTest extends TestCase
{
    protected $testObj;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testObj = new GetTechTipStats;
        $testTips = factory(TechTips::class, 25)->create();
        for($i = 5; $i < 25; $i++)
        {
            DB::update('UPDATE tech_tips SET created_at = "'. Carbon::now()->subDays(60).'" WHERE (tip_id = '.$testTips[$i]->tip_id.')');
        }
    }

    //  Test get the number of tips within the last 30 days
    public function test_get_tips_in_last_days()
    {
        $tips = $this->testObj->getTipsInLastDays();
        $this->assertEquals($tips, 5);
    }

    //  Test get the total number of tech tips in system
    public function test_get_total_tech_tip_count()
    {
        $tips = $this->testObj->getTotalTechTipCount();
        $this->assertEquals($tips, 25);
    }
}
