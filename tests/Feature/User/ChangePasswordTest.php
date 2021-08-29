<?php

namespace Tests\Feature\User;

use Tests\TestCase;

use App\Models\User;

class ChangePasswordTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('password.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('password.index'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'current_password'      => 'password',
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];

        $response = $this->post(route('password.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $data = [
            'current_password'      => 'password',
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];

        $response = $this->actingAs($user)->post(route('password.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas([
            'message' => 'Password successfully updated',
            'type'    => 'success',
        ]);
    }
}
