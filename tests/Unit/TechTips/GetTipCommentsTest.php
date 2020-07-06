<?php

namespace Tests\Unit\TechTips;

use App\Domains\TechTips\GetTipComments;
use App\TechTipComments;
use App\TechTips;
use Tests\TestCase;

class GetTipCommentsTest extends TestCase
{
    public function test_execute()
    {
        $tip = factory(TechTips::class)->create();
        $comment = factory(TechTipComments::class)->create(['tip_id' => $tip->tip_id]);

        $res = (new GetTipComments)->execute($tip->tip_id);
        $this->assertEquals([$comment->toArray()], $res->makeHidden(['User'])->toArray());
    }
}
