<?php

namespace Tests\Feature\TechTips;

use App\Models\User;
use Tests\TestCase;

class TechTipsSettingsTest extends TestCase
{
    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $response = $this->get(route('admin.tech-tips.settings.edit'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('admin.tech-tips.settings.edit'));
        $response->assertForbidden();
    }

    public function test_edit()
    {
        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('admin.tech-tips.settings.edit'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'allow_comments' => true,
            'allow_public' => true,
        ];

        $response = $this->put(route('admin.tech-tips.settings.edit'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $data = [
            'allow_comments' => true,
            'allow_public' => true,
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->put(route('admin.tech-tips.settings.edit'), $data);
        $response->assertForbidden();
    }

    public function test_update()
    {
        $data = [
            'allow_comments' => false,
            'allow_public' => true,
        ];

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->put(route('admin.tech-tips.settings.edit'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('tips.settings_updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_public',
            // 'value' => true,
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_comments',
            // 'value' => true,
        ]);
    }
}
