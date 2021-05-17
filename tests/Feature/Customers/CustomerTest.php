<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;

use App\Models\User;
use App\Models\Customer;
use App\Models\UserRolePermissions;

class CustomerTest extends TestCase
{
    /*
    *   Index Method
    */
    public function test_index_guest()
    {
        $response = $this->get(route('customers.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('customers.index'));
        $response->assertSuccessful();
    }

    /*
    *   Create Method
    */
    public function test_create_guest()
    {
        $response = $this->get(route('customers.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
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

    /*
    *   Store Method
    */
    public function test_store_guest()
    {
        $newCust = Customer::factory()->make();

        $response = $this->post(route('customers.store'), $newCust->toArray());
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_store_no_permission()
    {
        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermissions::whereRoleId(4)->wherePermTypeId(7)->update([
            'allow' => false,
        ]);
        $user    = User::factory()->create();
        $newCust = Customer::factory()->make();

        $response = $this->actingAs($user)->post(route('customers.store'), $newCust->only(['name', 'dba_name', 'address', 'city', 'state', 'zip']));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('customers', $newCust->only(['name', 'dba_name', 'address', 'city', 'state', 'zip']));
    }

    public function test_store()
    {
        $newCust = Customer::factory()->make();

        $response = $this->actingAs(User::factory()->create())->post(route('customers.store'), $newCust->only(['cust_id', 'parent_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip']));
        $response->assertStatus(302);
        $response->assertRedirect(route('customers.show', $newCust->slug));
        $this->assertDatabaseHas('customers', $newCust->only(['name', 'dba_name', 'address', 'city', 'state', 'zip']));
    }

    /*
    *   Show Method
    */
    public function test_show_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.show', $customer->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_show()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.show', $customer->slug));
        $response->assertSuccessful();
    }

    public function test_show_invalid()
    {
        $customer = Customer::factory()->make();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.show', $customer->slug));
        $response->assertStatus(404);
    }

    public function test_show_cust_id()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.show', $customer->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('customers.show', $customer->slug));
    }

    /*
    *   Update Method
    */
    public function test_update_guest()
    {
        $customer = Customer::factory()->create();
        $updated  = Customer::factory()->make();

        $response = $this->put(route('customers.update', $customer->cust_id), $updated->only(['name', 'address', 'city', 'state', 'zip']));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_update_no_permission()
    {
        //  Remove the "Update Customer" permission from the Tech Role
        UserRolePermissions::whereRoleId(4)->wherePermTypeId(8)->update([
            'allow' => false,
        ]);
        $customer = Customer::factory()->create();
        $updated  = Customer::factory()->make();

        $response = $this->actingAs(User::factory()->create())->put(route('customers.update', $customer->cust_id), $updated->only(['name', 'address', 'city', 'state', 'zip']));
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $customer = Customer::factory()->create();
        $updated  = Customer::factory()->make();

        $response = $this->actingAs(User::factory()->create())->put(route('customers.update', $customer->cust_id), $updated->only(['name', 'address', 'city', 'state', 'zip']));
        $response->assertStatus(302);
        $response->assertRedirect(route('customers.show', $updated->slug));
        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'name'    => $updated->name,
            'address' => $updated->address,
        ]);
    }

    /*
    *   Destroy Method
    */
    public function test_destroy_guest()
    {
        $cust = Customer::factory()->create();

        $response = $this->delete(route('customers.destroy', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_destroy_no_permission()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.destroy', $cust->cust_id));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('customers.destroy', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('customers.index'));
        $this->assertSoftDeleted('customers', $cust->only(['cust_id']));
    }

    /*
    *   Restore Method
    */
    public function test_restore_guest()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();

        $response = $this->get(route('customers.restore', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_restore_no_permission()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.restore', $cust->cust_id));
        $response->assertStatus(403);
    }

    public function test_restore()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('customers.restore', $cust->cust_id));
        $response->assertStatus(302);
        $this->assertDatabaseHas('customers', ['cust_id' => $cust->cust_id, 'deleted_at' => null]);
    }

    /*
    *   Force Delete Method
    */
    public function test_force_delete_guest()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();

        $response = $this->delete(route('customers.force-delete', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_force_delete_no_permission()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.force-delete', $cust->cust_id));
        $response->assertStatus(403);
    }

    public function test_force_delete()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('customers.force-delete', $cust->cust_id));
        $response->assertSuccessful();
        $this->assertDatabaseMissing('customers', $cust->only(['cust_id']));
    }
}
