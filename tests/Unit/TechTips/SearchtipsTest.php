<?php

namespace Tests\Unit\TechTips;

use App\Domains\TechTips\SearchTips;
use App\Http\Requests\TechTips\SearchTipsRequest;
use App\SystemTypes;
use App\TechTips;
use App\TechTipSystems;
use Tests\TestCase;

class SearchtipsTest extends TestCase
{
    public function setUp(): void
    {
        Parent::setup();

        $testData = [
            [
                'tip_id'      => 1,
                'public'      => 0,
                'tip_type_id' => 1,
                'subject'     => 'How to do something cool',
                'description' => 'Be Awesome!!!',
            ],
            [
                'tip_id'      => 2,
                'public'      => 0,
                'tip_type_id' => 1,
                'subject'     => 'How to Search for Tech Tips',
                'description' => 'Enter something that should be searched for',
            ],
            [
                'tip_id'      => 3,
                'public'      => 0,
                'tip_type_id' => 2,
                'subject'     => 'Tips for drinking whiskey',
                'description' => 'Drink it straight, or not at all!!!',
            ],
            [
                'tip_id'      => 4,
                'public'      => 0,
                'tip_type_id' => 2,
                'subject'     => 'Network addressing 101',
                'description' => 'An example IP Address would be 192.168.20.23.  A public IP Address would be 77.40.44.97',
            ],
            [
                'tip_id'      => 56896,
                'public'      => 0,
                'tip_type_id' => 3,
                'subject'     => 'Why switch to Laravel',
                'description' => 'Because.  Thats why',
            ],
        ];

        foreach($testData as $data)
        {
            factory(TechTips::class)->create($data);
        }
    }

    public function test_search_all_tips()
    {
        $searchData = new SearchTipsRequest([
            // 'search' => [
            //     'searchText'  => null,
            //     'articleType' => null,
            //     'sys_id'      => null,
            // ],
            'pagination' => [
                'low'     => null,
                'high'    => null,
                'perPage' => 25,
            ],
            'page' => 1,
        ]);

        $res = (new SearchTips)->execute($searchData);
        $this->assertCount(5, $res);
    }

    public function test_search_for_id()
    {
        $searchData = new SearchTipsRequest([
            'search' => [
                'text'   => '56896',
                'type'   => null,
                'sys_id' => null,
            ],
            'pagination' => [
                'low'     => null,
                'high'    => null,
                'perPage' => 25,
            ],
            'page' => 1,
        ]);

        $res = (new SearchTips)->execute($searchData);

        $this->assertCount(1, $res);
    }

    public function test_search_for_subject()
    {
        $searchData = new SearchTipsRequest([
            'search' => [
                'text'   => 'How to',
                'type'   => null,
                'sys_id' => null,
            ],
            'pagination' => [
                'low'     => null,
                'high'    => null,
                'perPage' => 25,
            ],
            'page' => 1,
        ]);

        $res = (new SearchTips)->execute($searchData);

        $this->assertCount(3, $res);
    }

    public function test_search_for_type()
    {
        $searchData = new SearchTipsRequest([
            'search' => [
                'text'   => null,
                'type'   => [1],
                'sys_id' => null,
            ],
            'pagination' => [
                'low'     => null,
                'high'    => null,
                'perPage' => 25,
            ],
            'page' => 1,
        ]);

        $res = (new SearchTips)->execute($searchData);

        $this->assertCount(2, $res);
    }

    public function test_search_for_sys()
    {
        $sys = factory(TechTipSystems::class)->create();

        $searchData = new SearchTipsRequest([
            'search' => [
                'text'   => null,
                'type'   => null,
                'sys_id' => [$sys->sys_id],
            ],
            'pagination' => [
                'low'     => null,
                'high'    => null,
                'perPage' => 25,
            ],
            'page' => 1,
        ]);

        $res = (new SearchTips)->execute($searchData);

        $this->assertCount(1, $res);
    }
}
