<?php

namespace Tests\Feature\Customer;

use App\Models\User;
use Tests\TestCase;

class DisabledCustomerTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('customers.disabled.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('customers.disabled.index'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('customers.disabled.index'));
        $response->assertSuccessful();
    }
}
