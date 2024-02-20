<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerIdTest extends TestCase
{
    /*
     *   Invoke Method
     */
    public function test_invoke_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.check-id', $customer->cust_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_id_in_use()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.check-id', $customer->cust_id));
        $response->assertSuccessful();
        $response->assertJson([
            'in_use' => true,
            'cust_name' => $customer->name,
            'route' => route('customers.show', $customer->slug),
        ]);
    }

    public function test_invoke_available_id()
    {
        $customer = 8971243298457;             //  This random number should hopefully never be duplicated

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.check-id', $customer));
        $response->assertSuccessful();
        $response->assertJson(['in_use' => false]);
    }
}
