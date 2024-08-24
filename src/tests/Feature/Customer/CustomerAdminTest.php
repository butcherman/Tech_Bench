<?php

namespace Tests\Feature\Customer;

use App\Models\User;
use Tests\TestCase;

class CustomerAdminTest extends TestCase
{
    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $response = $this->get(route('customers.settings.edit'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.settings.edit'));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('customers.settings.edit'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'select_id' => false,
            'update_slug' => false,
            'default_state' => 'OR',
            'auto_purge' => false,
        ];

        $response = $this->put(route('customers.settings.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $data = [
            'select_id' => false,
            'update_slug' => false,
            'default_state' => 'OR',
            'auto_purge' => false,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.settings.update'), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $data = [
            'select_id' => false,
            'update_slug' => false,
            'default_state' => 'OR',
            'auto_purge' => false,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('customers.settings.update'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Customer Settings Updated');

        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.select_id',
            // 'value' => false,
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.update_slug',
            // 'value' => false,
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.default_state',
            'value' => 'OR',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.auto_purge',
            // 'value' => false,
        ]);
    }
}
