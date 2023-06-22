<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppConfigTest extends TestCase
{
    /**
     * Get Method
     */
    public function test_get_guest()
    {
        $response = $this->get(route('admin.config.get'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_get_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.config.get'));
        $response->assertStatus(403);
    }

    public function test_get()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.config.get'));
        $response->assertSuccessful();
    }

    /**
     * Set Method
     */
    public function test_set_guest()
    {
        $data = [
            'timezone' => 'Pacific/Pitcairn',
            'max_filesize' => 123456,
            'url' => 'https://test_link.com',
        ];

        $response = $this->post(route('admin.config.set'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_set_no_permission()
    {
        $data = [
            'timezone' => 'Pacific/Pitcairn',
            'max_filesize' => 123456,
            'url' => 'https://test_link.com',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.config.set'), $data);
        $response->assertStatus(403);
    }

    public function test_set()
    {
        $data = [
            'timezone' => 'Pacific/Pitcairn',
            'max_filesize' => 123456,
            'url' => 'https://test_link.com',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.config.set'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.config.updated'));
        $this->assertDatabaseHas('app_settings', ['key' => 'app.timezone',             'value' => 'Pacific/Pitcairn']);
        $this->assertDatabaseHas('app_settings', ['key' => 'filesystems.max_filesize', 'value' => '123456']);
        $this->assertDatabaseHas('app_settings', ['key' => 'app.url',                  'value' => 'https://test_link.com']);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.redirect', 'value' => 'https://test_link.com/auth/callback']);
    }
}
