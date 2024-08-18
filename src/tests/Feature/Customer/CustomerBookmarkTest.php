<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerBookmarkTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $customer = Customer::factory()->create();
        $data = [
            'value' => true
        ];

        $response = $this->post(route('customers.bookmark', $customer->slug), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_add()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'value' => true
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.bookmark', $customer->slug), $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('user_customer_bookmarks', [
            'user_id' => $user->user_id,
            'cust_id' => $customer->cust_id,
        ]);
    }

    public function test_invoke_add_duplicate()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'value' => true
        ];

        $customer->Bookmarks()->attach($user);

        $response = $this->actingAs($user)
            ->post(route('customers.bookmark', $customer->slug), $data);
        $response->assertStatus(500);
    }

    public function test_invoke_remove()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'value' => false
        ];

        $customer->Bookmarks()->attach($user);

        $response = $this->actingAs($user)
            ->post(route('customers.bookmark', $customer->slug), $data);
        $response->assertSuccessful();
        $this->assertDatabaseMissing('user_customer_bookmarks', [
            'user_id' => $user->user_id,
            'cust_id' => $customer->cust_id,
        ]);
    }

    public function test_invoke_remove_duplicate()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'value' => false
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.bookmark', $customer->slug), $data);
        $response->assertSuccessful();
        $this->assertDatabaseMissing('user_customer_bookmarks', [
            'user_id' => $user->user_id,
            'cust_id' => $customer->cust_id,
        ]);
    }
}
