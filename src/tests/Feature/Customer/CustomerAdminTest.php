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
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.settings.edit'));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
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
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'select_id' => false,
            'update_slug' => false,
            'default_state' => 'OR',
            'auto_purge' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.settings.update'), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'select_id' => false,
            'update_slug' => false,
            'default_state' => 'OR',
            'auto_purge' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.settings.update'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.admin.settings_updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.select_id',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.update_slug',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.default_state',
            'value' => 'OR',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.auto_purge',
        ]);
    }
}
