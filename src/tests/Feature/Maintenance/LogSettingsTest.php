<?php

namespace Tests\Feature\Maintenance;

use App\Models\User;
use Tests\TestCase;

class LogSettingsTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('maint.log-settings.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('maint.log-settings.show'));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('maint.log-settings.show'));
        $response->assertSuccessful();
    }

    /**
     * Set Method
     */
    public function test_set_guest()
    {
        $data = [
            'days' => 30,
            'log_level' => 'debug',
        ];

        $response = $this->put(route('maint.log-settings.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_set_no_permission()
    {
        $data = [
            'days' => 30,
            'log_level' => 'debug',
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->put(route('maint.log-settings.update'), $data);
        $response->assertStatus(403);
    }

    public function test_set()
    {
        $data = [
            'days' => 120,
            'log_level' => 'critical',
        ];

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->put(route('maint.log-settings.update'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.auth.level',
            'value' => 'critical',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.daily.level',
            'value' => 'critical',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.auth.days',
            'value' => 120,
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.daily.days',
            'value' => 120,
        ]);
    }
}
