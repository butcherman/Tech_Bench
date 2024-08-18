<?php

namespace Tests\Feature\Config;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('admin.features.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.features.show'));
        $response->assertForbidden();
    }

    public function test_show()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.features.show'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => true,
        ];

        $response = $this->put(route('admin.features.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => true,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('admin.features.update'), $data);
        $response->assertForbidden();
    }

    public function test_update()
    {
        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => true,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('admin.features.update'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Feature Settings Updated');

        $this->assertDatabaseHas('app_settings', [
            'key' => 'fileLink.feature_enabled',
            'value' => true,
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'techTips.allow_public',
            'value' => true,
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'techTips.allow_comments',
            'value' => true,
        ]);
    }
}
