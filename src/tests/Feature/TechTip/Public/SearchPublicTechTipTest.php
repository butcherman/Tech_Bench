<?php

namespace Tests\Feature\TechTip\Public;

use App\Models\TechTip;
use App\Models\User;
use Tests\TestCase;

class SearchPublicTechTipTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_search_guest()
    {
        config(['tech-tips.allow_public' => true]);

        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $response = $this->post(route('publicTips.search', $searchData));

        $response->assertSuccessful();
        $this->assertGuest();
    }

    public function test_search()
    {
        config(['tech-tips.allow_public' => true]);

        TechTip::factory()->count(5)->create(['public' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $response = $this->actingAs($user)
            ->post(route('publicTips.search', $searchData));

        $response->assertSuccessful();
    }

    public function test_search_feature_disabled()
    {
        config(['tech-tips.allow_public' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $response = $this->actingAs($user)
            ->post(route('publicTips.search', $searchData));

        $response->assertStatus(404);
    }
}
