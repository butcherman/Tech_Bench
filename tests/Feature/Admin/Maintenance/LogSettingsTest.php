<?php

namespace Tests\Feature\Admin\Maintenance;

use App\Models\User;
use Tests\TestCase;

class LogSettingsTest extends TestCase
{
    /**
     * Get Method
     */
    public function test_get_guest()
    {
        $response = $this->get(route('admin.logs.settings.get'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_get_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.logs.settings.get'));
        $response->assertStatus(403);
    }

    public function test_get()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.settings.get'));
        $response->assertSuccessful();
    }

    /**
     * Set Method
     */
    public function test_set_guest()
    {
        $data = [
            'days' => 30,
            'level' => [
                'auth' => 'debug',
                'cust' => 'debug',
                'daily' => 'debug',
                'tip' => 'debug',
                'user' => 'debug',
            ],
        ];

        $response = $this->post(route('admin.logs.settings.set'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_set_no_permission()
    {
        $data = [
            'days' => 30,
            'level' => [
                'auth' => 'debug',
                'cust' => 'debug',
                'daily' => 'debug',
                'tip' => 'debug',
                'user' => 'debug',
            ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.logs.settings.set'), $data);
        $response->assertStatus(403);
    }

    public function test_set()
    {
        $data = [
            'days' => 30,
            'level' => [
                'auth' => 'debug',
                'cust' => 'debug',
                'daily' => 'debug',
                'tip' => 'debug',
                'user' => 'debug',
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.logs.settings.set'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('app_settings', ['key' => 'logging.days',      'value' => '30']);
        $this->assertDatabaseHas('app_settings', ['key' => 'logging.channels.auth.level', 'value' => 'debug']);
        $this->assertDatabaseHas('app_settings', ['key' => 'logging.channels.cust.level', 'value' => 'debug']);
        $this->assertDatabaseHas('app_settings', ['key' => 'logging.channels.daily.level', 'value' => 'debug']);
        $this->assertDatabaseHas('app_settings', ['key' => 'logging.channels.tip.level', 'value' => 'debug']);
        $this->assertDatabaseHas('app_settings', ['key' => 'logging.channels.user.level', 'value' => 'debug']);
    }
}
