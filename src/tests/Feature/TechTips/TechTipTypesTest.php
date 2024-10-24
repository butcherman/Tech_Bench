<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\TechTipType;
use App\Models\User;
use Tests\TestCase;

class TechTipTypesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.tech-tips.tip-types.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('admin.tech-tips.tip-types.index'));
        $response->assertForbidden();
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('admin.tech-tips.tip-types.index'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->post(route('admin.tech-tips.tip-types.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('admin.tech-tips.tip-types.store'), $data);
        $response->assertForbidden();
    }

    public function test_store()
    {
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->post(route('admin.tech-tips.tip-types.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('tips.tip-type.created'));

        $this->assertDatabaseHas('tech_tip_types', $data);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->put(route('admin.tech-tips.tip-types.update', $currentType->tip_type_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->put(route('admin.tech-tips.tip-types.update', $currentType->tip_type_id), $data);
        $response->assertForbidden();
    }

    public function test_update()
    {
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->put(route('admin.tech-tips.tip-types.update', $currentType->tip_type_id), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('tips.tip-type.updated'));

        $this->assertDatabaseHas('tech_tip_types', $data);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);

        $response = $this->delete(route('admin.tech-tips.tip-types.destroy', $currentType->tip_type_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->delete(route('admin.tech-tips.tip-types.destroy', $currentType->tip_type_id));
        $response->assertForbidden();
    }

    public function test_destroy()
    {
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->delete(route('admin.tech-tips.tip-types.destroy', $currentType->tip_type_id));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('tips.tip-type.deleted'));

        $this->assertDatabaseMissing('tech_tip_types', $currentType->only(['tip_type_id']));
    }

    public function test_destroy_in_use()
    {
        $currentType = TechTipType::create(['description' => 'Old Tip Type']);
        TechTip::factory()->create(['tip_type_id' => $currentType->tip_type_id]);

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->delete(route('admin.tech-tips.tip-types.destroy', $currentType->tip_type_id));
        $response->assertStatus(302);
        // $response->assertSessionHasErrors()
        $response->assertSessionHasErrors(['query_error' => __('tips.tip-type.in-use')]);

        $this->assertDatabaseHas('tech_tip_types', $currentType->only(['tip_type_id']));
    }
}
