<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BreakCustomerLinkTest extends TestCase
{
    /*
    *   Invoke Method
    */
    public function test_invoke_guest()
    {
        $cust = Customer::factory()->create([
            'parent_id' => Customer::factory()->create()->cust_id,
        ]);

        $response = $this->get(route('customers.break-link', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_invoke_no_permission()
    {
        $cust = Customer::factory()->create([
            'parent_id' => Customer::factory()->create()->cust_id,
        ]);

        $response = $this->actingAs(User::factory()->create())->get(route('customers.break-link', $cust->cust_id));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $cust = Customer::factory()->create([
            'parent_id' => Customer::factory()->create()->cust_id,
        ]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('customers.break-link', $cust->cust_id));
        $response->assertStatus(302);
        $this->assertDatabaseHas('customers', ['cust_id' => $cust->cust_id, 'parent_id' => null]);
    }
}
