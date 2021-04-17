<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;

use App\Models\User;
use App\Models\Customer;

class CheckCustIdTest extends TestCase
{
    /*
    *   Invoke Method
    */
    public function test_invoke_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->post(route('customers.check-id'), ['cust_id' => $customer->cust_id]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_invoke_id_in_use()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->post(route('customers.check-id'), ['cust_id' => $customer->cust_id]);
        $response->assertSuccessful();
        $response->assertJson(['valid' => false, 'data' => ['message' => 'Customer ID is taken by '.$customer->name]]);
    }

    public function test_invoke_available_id()
    {
        $customer = Customer::factory()->make();

        $response = $this->actingAs(User::factory()->create())->post(route('customers.check-id'), ['cust_id' => $customer->cust_id]);
        $response->assertSuccessful();
        $response->assertJson(['valid' => true, 'data' => ['message' => 'Customer ID is available']]);
    }
}
