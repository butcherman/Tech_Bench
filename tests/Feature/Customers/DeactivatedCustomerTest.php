<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;
use App\Models\User;

class DeactivatedCustomerTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.cust.show-deactivated'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.cust.show-deactivated'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.cust.show-deactivated'));
        $response->assertSuccessful();
    }
}
