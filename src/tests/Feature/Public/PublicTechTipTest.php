<?php

namespace Tests\Feature\Public;

use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\User;
use Tests\TestCase;

class PublicTechTipTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        config(['techTips.allow_public' => true]);

        $response = $this->get(route('publicTips.index'));
        $response->assertSuccessful();
        $this->assertGuest();
    }

    public function test_index()
    {
        config(['techTips.allow_public' => true]);

        EquipmentType::factory()->count(5)->create(['allow_public_tip' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('publicTips.index'));
        $response->assertSuccessful();
    }

    public function test_index_feature_disabled()
    {
        config(['techTips.allow_public' => false]);

        $response = $this->get(route('publicTips.index'));
        $response->assertStatus(404);
        $this->assertGuest();
    }

    /**
     * Search Method
     */
    public function test_search_guest()
    {
        config(['techTips.allow_public' => true]);

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
        config(['techTips.allow_public' => true]);
        TechTip::factory()->count(5)->create(['public' => true]);

        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('publicTips.search', $searchData));
        $response->assertSuccessful();
    }

    public function test_search_feature_disabled()
    {
        config(['techTips.allow_public' => false]);

        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('publicTips.search', $searchData));
        $response->assertStatus(404);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        config(['techTips.allow_public' => true]);

        $tip = TechTip::factory()->create(['public' => true]);

        $response = $this->get(route('publicTips.show', $tip->slug));
        $response->assertSuccessful();
        $this->assertGuest();
    }

    public function test_show()
    {
        config(['techTips.allow_public' => true]);

        $tip = TechTip::factory()->create(['public' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('publicTips.show', $tip->slug));
        $response->assertSuccessful();
    }

    public function test_show_private_tip_guest()
    {
        config(['techTips.allow_public' => true]);

        $tip = TechTip::factory()->create(['public' => false]);

        $response = $this->get(route('publicTips.show', $tip->slug));
        $response->assertStatus(404);
        $this->assertGuest();
    }

    public function test_show_feature_disabled()
    {
        config(['techTips.allow_public' => false]);

        $tip = TechTip::factory()->create(['public' => true]);

        $response = $this->get(route('publicTips.show', $tip->slug));
        $response->assertStatus(404);
        $this->assertGuest();
    }

    // TODO - Test show missing tech tip
}
