<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\User;
use Tests\TestCase;

class SearchTipsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setup();

        $testData = [
            [
                'tip_id' => 1,
                'user_id' => 1,
                'tip_type_id' => 1,
                'sticky' => false,
                'subject' => 'First Tech Tip',
                'details' => 'This is the very first test Tech Tip',
            ],
            [
                'tip_id' => 2,
                'user_id' => 1,
                'tip_type_id' => 2,
                'sticky' => false,
                'subject' => 'Second Tech Tip',
                'details' => 'Maecenas nec odio et ante',
            ],
            [
                'tip_id' => 3,
                'user_id' => 1,
                'tip_type_id' => 3,
                'sticky' => false,
                'subject' => 'Third Something Type of Randomness',
                'details' => 'Vivamus in erat ut urna',
            ],
            [
                'tip_id' => 4,
                'user_id' => 1,
                'tip_type_id' => 1,
                'sticky' => false,
                'subject' => 'Fourth blah blah blah',
                'details' => 'Donec quam felis ultricies nec',
            ],
            [
                'tip_id' => 5,
                'user_id' => 1,
                'tip_type_id' => 2,
                'sticky' => false,
                'subject' => 'Fifth something or other',
                'details' => 'Wu-Tang Forever!!!',
            ],
        ];
        foreach ($testData as $data) {
            TechTip::factory()->create($data);
        }
    }

    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $response = $this->post(route('tech-tips.search', $searchData));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_search_all_tips()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.search', $searchData));

        $response->assertSuccessful()
            ->assertJsonCount(5, 'data');
    }
}
