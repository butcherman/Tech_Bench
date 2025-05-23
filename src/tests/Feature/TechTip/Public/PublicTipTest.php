<?php

namespace Tests\Feature\TechTip\Public;

use App\Exceptions\TechTip\PublicTipsDisabledException;
use App\Exceptions\TechTip\TechTipNotPublicException;
use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicTipTest extends TestCase
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
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('TechTip/Public/Index')
                    ->has('filter-data')
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
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('TechTip/Public/Index')
                    ->has('filter-data')
            );
    }

    public function test_index_feature_disabled(): void
    {
        Exceptions::fake();

        config(['tech-tips.allow_public' => false]);

        $this->expectException(PublicTipsDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('publicTips.index'));

        $response->assertStatus(404);
        $this->assertGuest();

        Exceptions::assertReported(PublicTipsDisabledException::class);
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
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('TechTip/Public/Show')
                    ->has('tech-tip')
                    ->has('equipment')
                    ->has('files')
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
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('TechTip/Public/Show')
                    ->has('tech-tip')
                    ->has('equipment')
                    ->has('files')
            );
    }

    public function test_show_private_tip_guest(): void
    {
        Exceptions::fake();

        config(['tech-tips.allow_public' => true]);

        $tip = TechTip::factory()->create(['public' => false]);

        $this->expectException(TechTipNotPublicException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('publicTips.show', $tip->slug));

        $response->assertStatus(404);
        $this->assertGuest();

        Exceptions::assertReported(TechTipNotPublicException::class);
    }

    public function test_show_feature_disabled(): void
    {
        Exceptions::fake();

        config(['tech-tips.allow_public' => false]);

        $tip = TechTip::factory()->create(['public' => true]);

        $this->expectException(PublicTipsDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('publicTips.show', $tip->slug));

        $response->assertStatus(404);
        $this->assertGuest();

        Exceptions::assertReported(PublicTipsDisabledException::class);
    }

    public function test_show_missing_tip(): void
    {
        config(['tech-tips.allow_public' => true]);

        $response = $this->get(route('publicTips.show', 'random-slug'));

        $response->assertStatus(404);
        $this->assertGuest();
    }
}
