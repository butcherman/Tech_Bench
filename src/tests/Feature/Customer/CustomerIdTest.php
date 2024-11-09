<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;

class CustomerIdTest extends TestCase
{
    /*
     *   Invoke Method
     */
    public function test_invoke_guest(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.check-id', $customer->cust_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_id_in_use(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.check-id', $customer->cust_id));

        $response->assertSuccessful()
            ->assertJson([
                'in_use' => true,
                'cust_name' => $customer->name,
                'route' => route('customers.show', $customer->slug),
            ]);
    }

    public function test_invoke_available_id(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = 8971243298457;

        $response = $this->actingAs($user)
            ->get(route('customers.check-id', $customer));

        $response->assertSuccessful()
            ->assertJson(['in_use' => false]);
    }
}
