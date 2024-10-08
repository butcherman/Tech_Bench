<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;

class CustomerDeletedItemsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.deleted-items.index', $customer->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('customers.deleted-items.index', $customer->slug));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('customers.deleted-items.index', $customer->slug));
        $response->assertSuccessful();
    }
}
