<?php

namespace Tests\Unit\TechTips;

use App\Domains\TechTips\GetTechTipTypes;
use Tests\TestCase;

class GetTechTipTypesTest extends TestCase
{
    public function test_execute()
    {
        $defaultData = [
            ['tip_type_id' => 1, 'description' => 'Tech Tip',      ],
            ['tip_type_id' => 2, 'description' => 'Documentation', ],
            ['tip_type_id' => 3, 'description' => 'Software',      ],
        ];

        $res = (new GetTechTipTypes)->execute();
        $this->assertEquals($res->toArray(), $defaultData);
    }
}
