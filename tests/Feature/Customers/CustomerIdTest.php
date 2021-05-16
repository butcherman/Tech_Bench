<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;

use App\Models\User;
use App\Models\Customer;

class CustomerIdTest extends TestCase
{
    /*
    *   Index Method
    */
    public function test_index_guest()
    {
        $response = $this->get(route('customers.change-id.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('customers.change-id.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('customers.change-id.index'));
        $response->assertSuccessful();
    }

    /*
    *   Edit Function
    */
    public function test_edit_guest()
    {
        $cust = Customer::factory()->create();

        $response = $this->get(route('customers.change-id.edit', $cust->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_edit_no_permission()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.change-id.edit', $cust->slug));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('customers.change-id.edit', $cust->slug));
        $response->assertSuccessful();
    }

    /*
    *   Update Function
    */
    public function test_update_guest()
    {
        $cust = Customer::factory()->create(['cust_id' => 33]);
        $data = [
            'current_id' => $cust->cust_id,
            'cust_id'    => 99,
        ];

        $response = $this->put(route('customers.change-id.update', $cust->cust_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_update_no_permission()
    {
        $cust = Customer::factory()->create(['cust_id' => 33]);
        $data = [
            'current_id' => $cust->cust_id,
            'cust_id'    => 99,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('customers.change-id.update', $cust->cust_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $cust = Customer::factory()->create(['cust_id' => 33]);
        $data = [
            'current_id' => $cust->cust_id,
            'cust_id'    => 99,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('customers.change-id.update', $cust->cust_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.index'));
        $this->assertDatabaseHas('customers', ['cust_id' => $data['cust_id']]);
    }
}
