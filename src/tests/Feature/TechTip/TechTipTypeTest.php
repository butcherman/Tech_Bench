<?php

namespace Tests\Feature\TechTip;

use App\Models\TechTipType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TechTipTypeTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('admin.tech-tips.tip-types.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.tech-tips.tip-types.index'));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.tech-tips.tip-types.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('TechTip/Admin/Types')
                    ->has('tip-types')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->post(
            route('admin.tech-tips.tip-types.store'),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.tech-tips.tip-types.store'), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.tech-tips.tip-types.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.tip-type.created'));

        $this->assertDatabaseHas('tech_tip_types', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->put(
            route(
                'admin.tech-tips.tip-types.update',
                $currentType->tip_type_id
            ),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs($user)
            ->put(
                route(
                    'admin.tech-tips.tip-types.update',
                    $currentType->tip_type_id
                ),
                $data
            );

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs($user)
            ->put(
                route(
                    'admin.tech-tips.tip-types.update',
                    $currentType->tip_type_id
                ),
                $data
            );

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.tip-type.updated'));

        $this->assertDatabaseHas('tech_tip_types', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);

        $response = $this->delete(route(
            'admin.tech-tips.tip-types.destroy',
            $currentType->tip_type_id
        ));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);

        $response = $this->actingAs($user)
            ->delete(route(
                'admin.tech-tips.tip-types.destroy',
                $currentType->tip_type_id
            ));

        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);

        $response = $this->actingAs($user)
            ->delete(route(
                'admin.tech-tips.tip-types.destroy',
                $currentType->tip_type_id
            ));

        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('tips.tip-type.deleted'));

        $this->assertDatabaseMissing(
            'tech_tip_types',
            $currentType->only(['tip_type_id'])
        );
    }
}
