<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\User;
use App\Models\UserRolePermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
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

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $cust = Customer::factory()->create();

        $response = $this->get(route('customers.show', $cust->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.show', $cust->slug));
        $response->assertSuccessful();
    }

    public function test_show_with_cust_id()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.show', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('customers.show', $cust->slug));
    }

    public function test_show_invalid_customer()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('customers.show', 'ishkabibble'));
        $response->assertStatus(404);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $customer = Customer::factory()->create();
        $updated  = Customer::factory()->make();

        $response = $this->put(route('customers.update', $customer->cust_id), $updated->only(['name', 'address', 'city', 'state', 'zip']));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
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

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $cust = Customer::factory()->create();

        $response = $this->delete(route('customers.destroy', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.destroy', $cust->cust_id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('customers', $cust->only(['cust_id', 'name', 'address', 'city', 'state', 'zip']));
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
        $data = [
            'list' => [ $cust->toArray() ],
        ];

        $response = $this->post(route('customers.restore'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_restore_no_permission()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();
        $data = [
            'list' => [ $cust->toArray() ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.restore'), $data);
        $response->assertStatus(403);
    }

    public function test_restore()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();
        $data = [
            'list' => [ $cust->toArray() ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.restore'), $data);
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
        $data = [
            'list' => [ $cust->toArray() ],
        ];

        $response = $this->post(route('customers.force-delete'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();
        $data = [
            'list' => [ $cust->toArray() ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.force-delete'), $data);
        $response->assertStatus(403);
    }

    public function test_force_delete()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();
        $data = [
            'list' => [ $cust->toArray() ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.force-delete'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseMissing('customers', $cust->only(['cust_id']));
    }

    public function test_force_delete_with_file()
    {
        Storage::fake('default');

        $cust = Customer::factory()->create();
        $file = CustomerFile::factory()->create(['cust_id' => $cust->cust_id]);
        $cust->delete();
        $cust->save();
        $data = [
            'list' => [ $cust->toArray() ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.force-delete'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseMissing('customers', $cust->only(['cust_id']));
        $this->assertDatabaseMissing('file_uploads', $file->only(['file_id']));
    }
}
