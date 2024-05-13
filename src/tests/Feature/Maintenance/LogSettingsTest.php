<?php

namespace Tests\Feature\Maintenance;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $response = $this->actingAs(User::factory()->create())
            ->get(route('maint.log-settings.show'));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
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

        $response = $this->actingAs(User::factory()->create())
            ->put(route('maint.log-settings.update'), $data);
        $response->assertStatus(403);
    }

    public function test_set()
    {
        $data = [
            'days' => 30,
            'log_level' => 'debug',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('maint.log-settings.update'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.days',
            'value' => '30'
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.auth.level',
            'value' => 'debug'
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.cust.level',
            'value' => 'debug'
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.daily.level',
            'value' => 'debug'
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.tip.level',
            'value' => 'debug'
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.user.level',
            'value' => 'debug'
        ]);
    }
}
