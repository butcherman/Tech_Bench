<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetConfigTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'timezone' => 'Pacific/Pitcairn',
            'filesize' => 123456,
            'url'      => 'https://test_link.com',
        ];

        $response = $this->post(route('admin.set-config'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $data = [
            'timezone' => 'Pacific/Pitcairn',
            'filesize' => 123456,
            'url'      => 'https://test_link.com',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.set-config'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $data = [
            'timezone' => 'Pacific/Pitcairn',
            'filesize' => 123456,
            'url'      => 'https://test_link.com',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.set-config'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('app_settings', ['key' => 'app.timezone',             'value' => 'Pacific/Pitcairn']);
        $this->assertDatabaseHas('app_settings', ['key' => 'filesystems.max_filesize', 'value' => '123456']);
        $this->assertDatabaseHas('app_settings', ['key' => 'app.url',                  'value' => 'https://test_link.com']);
    }
}
