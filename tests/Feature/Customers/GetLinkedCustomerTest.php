<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;

class GetLinkedCustomerTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $cust     = Customer::factory()->create();
        $children = Customer::factory()->count(5)->create([
            'parent_id' => $cust->cust_id,
        ]);

        $response = $this->get(route('customers.linked', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $cust     = Customer::factory()->create();
        $children = Customer::factory()->count(5)->create([
            'parent_id' => $cust->cust_id,
        ]);

        $response = $this->actingAs(User::factory()->create())->get(route('customers.linked', $cust->slug));
        $response->assertSuccessful();
        $response->assertJson($children->toArray());
    }
}
