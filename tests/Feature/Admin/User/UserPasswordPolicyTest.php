<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserPasswordPolicyTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.users.password-policy.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.users.password-policy.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.users.password-policy.index'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'expire'             => '60',
            'min_length'         => '12',
            'contains_uppercase' => 'false',
            'contains_lowercase' => 'false',
            'contains_number'    => 'false',
            'contains_special'   => 'false',
        ];

        $response = $this->post(route('admin.users.password-policy.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'expire'             => '60',
            'min_length'         => '12',
            'contains_uppercase' => 'false',
            'contains_lowercase' => 'false',
            'contains_number'    => 'false',
            'contains_special'   => 'false',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.users.password-policy.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'expire'             => '60',
            'min_length'         => '12',
            'contains_uppercase' => false,
            'contains_lowercase' => false,
            'contains_number'    => false,
            'contains_special'   => false,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.password-policy.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.password_policy'));
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.passwords.settings.expire',             'value' => $data['expire']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.passwords.settings.min_length',         'value' => $data['min_length']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.passwords.settings.contains_uppercase', 'value' => $data['contains_uppercase']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.passwords.settings.contains_lowercase', 'value' => $data['contains_lowercase']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.passwords.settings.contains_number',    'value' => $data['contains_number']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.passwords.settings.contains_special',   'value' => $data['contains_special']]);

    }
}
