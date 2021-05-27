<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeactivatedCustomersTest extends TestCase
{
    /*
    *   Invoke Method
    */
    public function test_invoke_guest()
    {
        $response = $this->get(route('customers.show-deactivated'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('customers.show-deactivated'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $customers = Customer::factory()->count(3)->create();
        foreach($customers as $cust)
        {
            Customer::find($cust->cust_id)->delete();
        }

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('customers.show-deactivated'));
        $response->assertSuccessful();
    }
}
