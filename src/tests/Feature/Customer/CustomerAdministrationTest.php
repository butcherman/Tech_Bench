<?php

namespace Tests\Feature\Customer;

use App\Models\User;
use Tests\TestCase;

class CustomerAdministrationTest extends TestCase
{
    /**
     * Edit Method
     */
    public function test_edit_guest(): void
    {
        $response = $this->get(route('customers.settings.edit'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.settings.edit'));

        $response->assertForbidden();
    }

    public function test_edit(): void
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
    public function test_update_guest(): void
    {
        $data = [
            'select_id' => false,
            'update_slug' => false,
            'default_state' => 'OR',
            'auto_purge' => false,
        ];

        $response = $this->put(route('customers.settings.update'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
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

        $response->assertForbidden();
    }

    public function test_update(): void
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

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.admin.settings_updated'));

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