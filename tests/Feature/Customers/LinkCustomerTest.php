<?php

namespace Tests\Feature\Customers;

use App\Models\User;
use App\Models\Customer;

use Tests\TestCase;

class LinkCustomerTest extends TestCase
{
    /*
    *   Invoke Method
    */
    public function test_invoke_guest()
    {
        $customer = Customer::factory()->create();
        $parent   = Customer::factory()->create();

        $response = $this->post(route('customers.link-customer'), ['cust_id' => $customer->cust_id, 'parent_id' => $parent->cust_id]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_invoke_no_permission()
    {
        $customer = Customer::factory()->create();
        $parent   = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->post(route('customers.link-customer'), ['cust_id' => $customer->cust_id, 'parent_id' => $parent->cust_id]);
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $customer = Customer::factory()->create();
        $parent   = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.link-customer'), ['cust_id' => $customer->cust_id, 'parent_id' => $parent->cust_id]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('customers', ['cust_id' => $customer->cust_id, 'parent_id' => $parent->cust_id]);
    }

    public function test_invoke_with_another_parent()
    {
        $parent   = Customer::factory()->create();
        $customer = Customer::factory()->create();
        $cust2    = Customer::factory()->create(['parent_id' => $parent->cust_id]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.link-customer'), ['cust_id' => $customer->cust_id, 'parent_id' => $cust2->cust_id]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('customers', ['cust_id' => $customer->cust_id, 'parent_id' => $parent->cust_id]);
    }
}
