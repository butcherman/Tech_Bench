<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasswordPolicyControllerTest extends TestCase
{
    public function test_submit_password_policy_guest()
    {
        $response = $this->post(route('admin.user.submit_password_policy'), ['expire' => 30]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_password_policy_no_permission()
    {
        $response = $this->actingAs($this->getUserWithoutPermission('Manage Users'))->post(route('admin.user.submit_password_policy'), ['expire' => 30]);
        $response->assertStatus(403);
    }

    public function test_submit_password_policy()
    {
        $response = $this->actingAs($this->getUserWithPermission('Manage Users'))->post(route('admin.user.submit_password_policy'), ['expire' => 30]);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
