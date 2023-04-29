<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use App\Models\UserCustomerBookmark;
use Tests\TestCase;

class CustomerBookmarkTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->post(route('customers.bookmark'), ['cust_id' => $customer->cust_id, 'state' => true]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_invoke_add()
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('customers.bookmark'), ['cust_id' => $customer->cust_id, 'state' => true]);
        $response->assertSuccessful();
        $response->assertStatus(204);
        $this->assertDatabaseHas('user_customer_bookmarks', ['user_id' => $user->user_id, 'cust_id' => $customer->cust_id]);
    }

    public function test_invoke_remove()
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create();
        UserCustomerBookmark::create([
            'cust_id' => $customer->cust_id,
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->post(route('customers.bookmark'), ['cust_id' => $customer->cust_id, 'state' => false]);
        $response->assertSuccessful();
        $response->assertStatus(204);
        $this->assertDatabaseMissing('user_customer_bookmarks', ['user_id' => $user->user_id, 'cust_id' => $customer->cust_id]);
    }

    public function test_invoke_add_when_already_there()
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create();
        UserCustomerBookmark::create([
            'cust_id' => $customer->cust_id,
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->post(route('customers.bookmark'), ['cust_id' => $customer->cust_id, 'state' => true]);
        $response->assertStatus(409);
    }
}
