<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;

class CustomerIdTest extends TestCase
{
    /*
    *   Index Method
    */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.cust.change_id.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.cust.change_id.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.cust.change_id.index'));
        $response->assertSuccessful();
    }

    /*
    *   Show Function
    */
    public function test_show_guest()
    {
        $cust = Customer::factory()->create();

        $response = $this->get(route('admin.cust.change_id.show', $cust->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.cust.change_id.show', $cust->slug));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.cust.change_id.show', $cust->slug));
        $response->assertSuccessful();
    }

    /*
    *   Update Function
    */
    public function test_update_guest()
    {
        $cust = Customer::factory()->create(['cust_id' => 33]);
        $data = [
            'cust_id'    => 99,
        ];

        $response = $this->put(route('admin.cust.change_id.update', $cust->cust_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $cust = Customer::factory()->create(['cust_id' => 33]);
        $data = [
            'cust_id'    => 99,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('admin.cust.change_id.update', $cust->slug), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $cust = Customer::factory()->create(['cust_id' => 33]);
        $data = [
            'cust_id'    => 99,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.cust.change_id.update', $cust->slug), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('customers.show', $cust->slug));
        $this->assertDatabaseHas('customers', ['cust_id' => $data['cust_id']]);
    }
}
