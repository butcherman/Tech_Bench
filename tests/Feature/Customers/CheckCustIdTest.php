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

        $response = $this->get(route('customers.check-id', $customer->cust_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_id_in_use()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.check-id', $customer->cust_id));
        $response->assertSuccessful();
        $response->assertJson(['valid' => false, 'name' => $customer->name]);
    }

    public function test_invoke_available_id()
    {
        $customer = 8971243298457;             //  This random number should hopefull never be duplicated

        $response = $this->actingAs(User::factory()->create())->get(route('customers.check-id', $customer));
        $response->assertSuccessful();
        $response->assertJson(['valid' => true]);
    }
}
