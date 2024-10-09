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
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.links.settings.show'));
        $response->assertForbidden();
    }

    public function test_show_no_permission()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.links.settings.show'));
        $response->assertForbidden();
    }

    public function test_show()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.links.settings.show'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'default_link_life' => '30',
            'auto_delete' => true,
            'auto_delete_days' => '365',
            'auto_delete_override' => false,
        ];

        $response = $this->put(route('admin.links.settings.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'default_link_life' => '30',
            'auto_delete' => true,
            'auto_delete_days' => '365',
            'auto_delete_override' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.links.settings.update'), $data);
        $response->assertForbidden();
    }

    public function test_update_no_permission()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'default_link_life' => '30',
            'auto_delete' => true,
            'auto_delete_days' => '365',
            'auto_delete_override' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.links.settings.update'), $data);
        $response->assertForbidden();
    }

    public function test_update()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'default_link_life' => '30',
            'auto_delete' => false,
            'auto_delete_days' => '365',
            'auto_delete_override' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.links.settings.update'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.default_link_life',
            'value' => '30',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete_days',
            'value' => '365',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete_override',
        ]);
    }
}
