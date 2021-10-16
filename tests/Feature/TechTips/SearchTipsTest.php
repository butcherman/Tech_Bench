<?php

namespace Tests\Feature\TechTips;

use App\Models\EquipmentType;
use Tests\TestCase;
use App\Models\TechTip;
use App\Models\TechTipEquipment;
use App\Models\User;
use Database\Factories\EquipmentTypeFactory;

class SearchTipsTest extends TestCase
{
    public function setUp(): void
    {
        Parent::setup();

        $testData = [
            [
                'tip_id'      => 1,
                'user_id'     => 1,
                'tip_type_id' => 1,
                'sticky'      => false,
                'subject'     => 'First Tech Tip',
                'details'     => 'This is the very first test Tech Tip',
            ],
            [
                'tip_id'      => 2,
                'user_id'     => 1,
                'tip_type_id' => 2,
                'sticky'      => false,
                'subject'     => 'Second Tech Tip',
                'details'     => 'Maecenas nec odio et ante',
            ],
            [
                'tip_id'      => 3,
                'user_id'     => 1,
                'tip_type_id' => 3,
                'sticky'      => false,
                'subject'     => 'Third Something Type of Randomness',
                'details'     => 'Vivamus in erat ut urna',
            ],
            [
                'tip_id'      => 4,
                'user_id'     => 1,
                'tip_type_id' => 1,
                'sticky'      => false,
                'subject'     => 'Fourth blah blah blah',
                'details'     => 'Donec quam felis ultricies nec',
            ],
            [
                'tip_id'      => 5,
                'user_id'     => 1,
                'tip_type_id' => 2,
                'sticky'      => false,
                'subject'     => 'Fifth something or other',
                'details'     => 'Wu-Tang Forever!!!',
            ],
        ];
        foreach($testData as $data)
        {
            TechTip::factory()->create($data);
        }
    }

    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $searchData = [
            'pagination_perPage' => 25,
            'page'               => 1,
        ];

        $response = $this->get(route('tips.search', $searchData));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_search_all_tips()
    {
        $searchData = [
            'pagination_perPage' => 25,
            'page'               => 1,
        ];

        $response = $this->actingAs(User::factory()->create())->get(route('tips.search', $searchData));
        $response->assertSuccessful();
        $response->assertJsonCount(5, 'data');
    }

    public function test_search_subject_text()
    {
        $searchData = [
            'pagination_perPage' => 25,
            'page'               => 1,
            'search_text'        => 'First Tech Tip',
        ];

        $response = $this->actingAs(User::factory()->create())->get(route('tips.search', $searchData));
        $response->assertSuccessful();
        $response->assertJsonCount(2, 'data');
    }

    public function test_search_details_text()
    {
        $searchData = [
            'pagination_perPage' => 25,
            'page'               => 1,
            'search_text'        => 'Wu-Tang',
        ];

        $response = $this->actingAs(User::factory()->create())->get(route('tips.search', $searchData));
        $response->assertSuccessful();
        $response->assertJsonCount(1, 'data');
    }

    public function test_search_equipment()
    {
        $equip = EquipmentType::factory()->create();
        for($i = 1; $i < 4; $i++)
        {
            TechTipEquipment::create([
                'tip_id'   => $i,
                'equip_id' => $equip->equip_id,
            ]);
        }

        $searchData = [
            'pagination_perPage' => 25,
            'page'               => 1,
            'search_equip_id'    => [$equip->equip_id],
        ];

        $response = $this->actingAs(User::factory()->create())->get(route('tips.search', $searchData));
        $response->assertSuccessful();
        $response->assertJsonCount(3, 'data');
    }

    public function test_search_type()
    {
        $searchData = [
            'pagination_perPage' => 25,
            'page'               => 1,
            'search_type'        => [1],
        ];

        $response = $this->actingAs(User::factory()->create())->get(route('tips.search', $searchData));
        $response->assertSuccessful();
        $response->assertJsonCount(2, 'data');
    }
}
