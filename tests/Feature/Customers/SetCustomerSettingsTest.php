<?php

namespace Tests\Feature\Customers;

use App\Models\User;
use Tests\TestCase;

class SetCustomerSettingsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'selectId' => false,
            'updateSlug' => false,
            'defaultState' => 'CA',
        ];

        $response = $this->post(route('admin.cust.set-settings'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $data = [
            'selectId' => false,
            'updateSlug' => false,
            'defaultState' => 'CA',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.cust.set-settings'), $data);

        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $data = [
            'selectId' => false,
            'updateSlug' => false,
            'defaultState' => 'CA',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.cust.set-settings'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.config_updated'));
        $this->assertDatabaseHas('app_settings', ['key' => 'customer.select_id',     'value' => false]);
        $this->assertDatabaseHas('app_settings', ['key' => 'customer.update_slug',   'value' => false]);
        $this->assertDatabaseHas('app_settings', ['key' => 'customer.default_state', 'value' => 'CA']);

    }
}
