<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\TechTipType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TechTipTypesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.tips.tip-types.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.tips.tip-types.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.tips.tip-types.index'));
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

        $response = $this->post(route('admin.tips.tip-types.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.tips.tip-types.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'description' => 'New Tip Type',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.tips.tip-types.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas([
            'message' => 'New Tech Tip Type created',
            'type'    => 'success',
        ]);
        $this->assertDatabaseHas('tech_tip_types', $data);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'description' => 'New Tip Type',
            'tip_type_id' => 1,
        ];

        $response = $this->put(route('admin.tips.tip-types.update', 1), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $data = [
            'description' => 'New Tip Type',
            'tip_type_id' => 1,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('admin.tips.tip-types.update', 1), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $data = [
            'description' => 'New Tip Type',
            'tip_type_id' => 1,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.tips.tip-types.update', 1), $data);
        $response->assertStatus(302);
        $response->assertSessionHas([
            'message' => 'Tech Tip Type has been updated',
            'type'    => 'success',
        ]);
        $this->assertDatabaseHas('tech_tip_types', $data);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $response = $this->delete(route('admin.tips.tip-types.destroy', 1));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->delete(route('admin.tips.tip-types.destroy', 1));
        $response->assertStatus(403);
    }

    public function test_destroy_in_use()
    {
        TechTip::factory()->create(['tip_type_id' => 1]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.tips.tip-types.destroy', 1));
        $response->assertStatus(302);
        $response->assertSessionHas([
            'message' => 'Unable to delete.  This Tech Tip Type is in use by some Tech Tips.',
            'type'    => 'danger',
        ]);
        $this->assertDatabaseHas('tech_tip_types', [
            'tip_type_id' => 1,
        ]);
    }

    public function test_destroy()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.tips.tip-types.destroy', 1));
        $response->assertStatus(302);
        $response->assertSessionHas([
            'message' => 'Tech Tip Type Deleted Successfully',
            'type'    => 'success',
        ]);
        $this->assertDatabaseMissing('tech_tip_types', [
            'tip_type_id' => 1,
        ]);
    }
}
