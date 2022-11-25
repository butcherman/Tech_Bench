<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;

class SetLinkedCustomerTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_add_link_guest()
    {
        $data = [
            'cust_id'   => Customer::factory()->create()->cust_id,
            'parent_id' => Customer::factory()->create()->cust_id,
            'add'       => true,
        ];

        $response = $this->post(route('customers.set-link'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_add_link_no_permission()
    {
        $data = [
            'cust_id'   => Customer::factory()->create()->cust_id,
            'parent_id' => Customer::factory()->create()->cust_id,
            'add'       => true,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.set-link'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke_add_link()
    {
        $data = [
            'cust_id'   => Customer::factory()->create()->cust_id,
            'parent_id' => Customer::factory()->create()->cust_id,
            'add'       => true,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.set-link'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.linked.set'));
        $this->assertDatabaseHas('customers', [
            'cust_id'   => $data['cust_id'],
            'parent_id' => $data['parent_id'],
        ]);
    }

    public function test_invoke_add_link_with_existing_parent()
    {
        $parent = Customer::Factory()->create(['parent_id' => Customer::factory()->create()->cust_id]);
        $data = [
            'cust_id'   => Customer::factory()->create()->cust_id,
            'parent_id' => $parent->cust_id,
            'add'       => true,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.set-link'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.linked.set'));
        $this->assertDatabaseHas('customers', [
            'cust_id'   => $data['cust_id'],
            'parent_id' => $parent->parent_id,
        ]);
    }

    public function test_invoke_remove_link_guest()
    {
        $customer = Customer::factory()->create([
            'parent_id' => Customer::factory()->create()->cust_id,
        ]);
        $data = [
            'cust_id'   => $customer->cust_id,
            'parent_id' => null,
            'add'       => false,
        ];

        $response = $this->post(route('customers.set-link'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_remove_link_no_permission()
    {
        $customer = Customer::factory()->create([
            'parent_id' => Customer::factory()->create()->cust_id,
        ]);
        $data = [
            'cust_id'   => $customer->cust_id,
            'parent_id' => null,
            'add'       => false,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.set-link'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke_remove_link()
    {
        $customer = Customer::factory()->create([
            'parent_id' => Customer::factory()->create()->cust_id,
        ]);
        $data = [
            'cust_id'   => $customer->cust_id,
            'parent_id' => null,
            'add'       => false,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.set-link'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.linked.removed'));
        $this->assertDatabaseHas('customers', [
            'cust_id'   => $data['cust_id'],
            'parent_id' => null,
        ]);
    }
}
