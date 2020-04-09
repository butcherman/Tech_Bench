<?php

namespace Tests\Feature\TechTips;

use App\TechTips;
use Tests\TestCase;
use App\TechTipSystems;

class TechTipsIndexTest extends TestCase
{
    protected $tips, $sysArr;

    public function setUp():void
    {
        Parent::setup();

        $this->tips = factory(TechTips::class, 25)->create();
        foreach($this->tips as $tip)
        {
            $data = factory(TechTipSystems::class)->create([
                'tip_id' => $tip->tip_id,
            ]);
            $this->sysArr[] = $data->sys_id;
        }
    }

    //  Visit the index page as a guest
    public function test_index_page_as_guest()
    {
        $response = $this->get(route('tips.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Visit the index page as valid user
    public function test_index_page()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('tips.index'));

        $response->assertSuccessful();
        $response->assertViewIs('tips.index');
    }

    //  Try to retrieve the list of tech tips as a guest
    public function test_get_tips_as_guest()
    {

        $data = [
            'search' => [
                'searchText' => '',
                // 'articleType' => [],
                // 'systemType' => [],
            ],
            'pagination' => [
                'rows' => '',
                'low'  => '',
                'high' => '',
                'perPage' => 10
            ]
        ];
        $response = $this->get(route('tip.search', $data));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Retrieve tech tips without any search filtering
    public function test_get_tips()
    {
        $user = $this->getTech();
        $data = [
            'search' => [
                'searchText' => '',
                // 'articleType' => [],
                // 'systemType' => [],
            ],
            'pagination' => [
                'rows' => '',
                'low'  => '',
                'high' => '',
                'perPage' => 10
            ]
        ];
        $response = $this->actingAs($user)->get(route('tip.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(['data', 'meta']);
    }

    //  Retrieve tech tips that match a string
    public function test_get_tips_search_by_name()
    {
        $user = $this->getTech();
        $data = [
            'search' => [
                'searchText' => $this->tips[2]->subject,
                // 'articleType' => [],
                // 'systemType' => [],
            ],
            'pagination' => [
                'rows' => '',
                'low'  => '',
                'high' => '',
                'perPage' => 10
            ]
        ];
        $response = $this->actingAs($user)->get(route('tip.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(['data', 'meta']);
    }

    //  Retrieve tech tips, include an article type this time
    public function test_get_tips_search_by_article_type()
    {
        $user = $this->getTech();
        $data = [
            'search' => [
                'searchText' => $this->tips[2]->subject,
                'articleType' => [$this->tips[2]->tip_type_id],
                // 'systemType' => [],
            ],
            'pagination' => [
                'rows' => '',
                'low'  => '',
                'high' => '',
                'perPage' => 10
            ]
        ];
        $response = $this->actingAs($user)->get(route('tip.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(['data', 'meta']);
    }

    //  Retrieve tech tips, search by system type this time
    public function test_get_tips_search_by_system_type()
    {
        $user = $this->getTech();
        $data = [
            'search' => [
                'searchText' => $this->tips[2]->subject,
                // 'articleType' => [$this->tips[2]->tip_type_id],
                'systemType' => [$this->sysArr[0]],
            ],
            'pagination' => [
                'rows' => '',
                'low'  => '',
                'high' => '',
                'perPage' => 10
            ]
        ];
        $response = $this->actingAs($user)->get(route('tip.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(['data', 'meta']);
    }
}
