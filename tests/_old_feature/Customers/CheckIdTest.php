<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckIdTest extends TestCase
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
        $this->assertGuest();
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
        $customer = 8971243298457;             //  This random number should hopefull never be duplicated

        $response = $this->actingAs(User::factory()->create())->post(route('customers.check-id'), ['cust_id' => $customer]);
        $response->assertSuccessful();
        $response->assertJson(['valid' => true, 'data' => ['message' => 'Customer ID is available']]);
    }
}
