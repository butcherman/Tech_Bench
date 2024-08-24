<?php

namespace Tests\Feature\FileLink;

use App\Models\User;
use Tests\TestCase;

class FileLinkSettingsTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('admin.links.settings.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_feature_disabled()
    {
        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.links.settings.show'));
        $response->assertForbidden();
    }

    public function test_show_no_permission()
    {
        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.links.settings.show'));
        $response->assertForbidden();
    }

    public function test_show()
    {
        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.links.settings.show'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'default_link_life' => 30,
            'auto_delete' => true,
            'auto_delete_days' => 365,
            'auto_delete_override' => false,
        ];

        $response = $this->put(route('admin.links.settings.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_feature_disabled()
    {
        $data = [
            'default_link_life' => 30,
            'auto_delete' => true,
            'auto_delete_days' => 365,
            'auto_delete_override' => false,
        ];

        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('admin.links.settings.update'), $data);
        $response->assertForbidden();
    }

    public function test_update_no_permission()
    {
        $data = [
            'default_link_life' => 30,
            'auto_delete' => true,
            'auto_delete_days' => 365,
            'auto_delete_override' => false,
        ];

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('admin.links.settings.update'), $data);
        $response->assertForbidden();
    }

    public function test_update()
    {
        $data = [
            'default_link_life' => 30,
            'auto_delete' => false,
            'auto_delete_days' => 365,
            'auto_delete_override' => false,
        ];

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('admin.links.settings.update'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'fileLink.default_link_life',
            'value' => 30,
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'fileLink.auto_delete',
            // 'value' => true,
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'fileLink.auto_delete_days',
            'value' => 365,
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'fileLink.auto_delete_override',
            // 'value' => false,
        ]);
    }
}
