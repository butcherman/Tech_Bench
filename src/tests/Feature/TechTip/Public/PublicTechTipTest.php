<?php

namespace Tests\Feature\TechTip\Public;

use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicTechTipTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        config(['tech-tips.allow_public' => true]);

        $response = $this->get(route('publicTips.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/TechTips/Index')
                ->has('equip-types')
            );
        $this->assertGuest();
    }

    public function test_index(): void
    {
        config(['tech-tips.allow_public' => true]);

        EquipmentType::factory()
            ->count(5)
            ->create(['allow_public_tip' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('publicTips.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/TechTips/Index')
                ->has('equip-types')
            );
    }

    public function test_index_feature_disabled(): void
    {
        config(['tech-tips.allow_public' => false]);

        $response = $this->get(route('publicTips.index'));

        $response->assertStatus(404);
        $this->assertGuest();
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        config(['tech-tips.allow_public' => true]);

        $tip = TechTip::factory()->create(['public' => true]);

        $response = $this->get(route('publicTips.show', $tip->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/TechTips/Show')
                ->has('tip-data')
                ->has('tip-equipment')
                ->has('tip-files')
            );
        $this->assertGuest();
    }

    public function test_show(): void
    {
        config(['tech-tips.allow_public' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create(['public' => true]);

        $response = $this->actingAs($user)
            ->get(route('publicTips.show', $tip->slug));
        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/TechTips/Show')
                ->has('tip-data')
                ->has('tip-equipment')
                ->has('tip-files')
            );
    }

    public function test_show_private_tip_guest(): void
    {
        config(['tech-tips.allow_public' => true]);

        $tip = TechTip::factory()->create(['public' => false]);

        $response = $this->get(route('publicTips.show', $tip->slug));

        $response->assertStatus(404);
        $this->assertGuest();
    }

    public function test_show_feature_disabled(): void
    {
        config(['tech-tips.allow_public' => false]);

        $tip = TechTip::factory()->create(['public' => true]);

        $response = $this->get(route('publicTips.show', $tip->slug));

        $response->assertStatus(404);
        $this->assertGuest();
    }

    public function test_show_missing_tip(): void
    {
        config(['tech-tips.allow_public' => true]);

        $response = $this->get(route('publicTips.show', 'random-slug'));

        $response->assertStatus(404);
        $this->assertGuest();
    }
}
