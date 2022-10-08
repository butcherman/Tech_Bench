<?php

namespace Tests\Feature\Admin\Logs;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetLogSettingsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'days'  => 30,
            'level' => 'debug',
        ];

        $response = $this->post(route('admin.logs.set-settings'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $data = [
            'days'  => 30,
            'level' => 'debug',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.logs.set-settings'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $data = [
            'days'  => 30,
            'level' => 'debug',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.logs.set-settings'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('app_settings', ['key' => 'logging.days',      'value' => '30']);
        $this->assertDatabaseHas('app_settings', ['key' => 'logging.log_level', 'value' => 'debug']);
    }
}
