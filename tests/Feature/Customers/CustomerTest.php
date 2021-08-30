<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use App\Models\UserRolePermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class CustomerTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('customers.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('customers.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('customers.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermissions::whereRoleId(4)->wherePermTypeId(7)->update([
            'allow' => false,
        ]);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('customers.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('customers.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = Customer::factory()->make()->only(['name', 'dba_name', 'address', 'city', 'state', 'zip']);

        $response = $this->post(route('customers.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
        $this->assertDatabaseMissing('customers', $data);
    }

    public function test_store_no_permission()
    {
        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermissions::whereRoleId(4)->wherePermTypeId(7)->update([
            'allow' => false,
        ]);
        $user = User::factory()->create();
        $data = Customer::factory()->make()->only(['name', 'dba_name', 'address', 'city', 'state', 'zip']);

        $response = $this->ActingAs($user)->post(route('customers.store'), $data);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('customers', $data);
    }

    public function test_store()
    {
        $data = Customer::factory()->make()->only(['name', 'dba_name', 'address', 'city', 'state', 'zip']);
        $slug = Str::slug($data['name']);

        $response = $this->ActingAs(User::factory()->create())->post(route('customers.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('customers.show', $slug));
        $this->assertDatabaseHas('customers', $data);
    }
}
