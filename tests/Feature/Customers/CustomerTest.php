<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\UserRolePermissions;

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
        $response->assertSessionHas('success', __('cust.created'));
        $response->assertRedirect(route('customers.show', $slug));
        $this->assertDatabaseHas('customers', $data);
    }

    public function test_store_duplicate_slug()
    {
        $existing = Customer::factory()->create();
        $data     = Customer::factory()->make()->only(['address', 'city', 'state', 'zip']);
        $data['name'] = $existing['name'];
        $slug     = Str::slug($data['name'].' '.$data['city']);

        $response = $this->ActingAs(User::factory()->create())->post(route('customers.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.created'));
        $response->assertRedirect(route('customers.show', $slug));
        $this->assertDatabaseHas('customers', $data);
    }

    public function test_store_second_duplicate_slug()
    {
        $existing1 = Customer::factory()->create();
        $existing2 = Customer::factory()->create(['name' => $existing1->name, 'slug' => Str::slug($existing1->slug.'-'.$existing1->city)]);

        $data     = Customer::factory()->make()->only(['address', 'city', 'state', 'zip']);
        $data['name'] = $existing1->name;
        $data['city'] = $existing1->city;
        $slug     = Str::slug($data['name'].' '.$data['city'].'-1');

        $response = $this->ActingAs(User::factory()->create())->post(route('customers.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.created'));
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

        $response = $this->put(route('customers.update', $customer->slug), $updated->only(['name', 'address', 'city', 'state', 'zip']));
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

        $response = $this->actingAs(User::factory()->create())->put(route('customers.update', $customer->slug), $updated->only(['name', 'address', 'city', 'state', 'zip']));
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $customer = Customer::factory()->create();
        $updated  = Customer::factory()->make();

        $response = $this->actingAs(User::factory()->create())->put(route('customers.update', $customer->slug), $updated->only(['name', 'address', 'city', 'state', 'zip']));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.updated'));
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

        $response = $this->delete(route('customers.destroy', $cust->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.destroy', $cust->slug));
        $response->assertStatus(403);
        $this->assertDatabaseHas('customers', $cust->only(['cust_id', 'name', 'address', 'city', 'state', 'zip']));
    }

    public function test_destroy()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('customers.destroy', $cust->slug));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.destroy'));
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
            'cust_list' => [ $cust->toArray() ],
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
            'cust_list' => [ $cust->toArray() ],
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
            'cust_list' => [ $cust->cust_id ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('customers.restore'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.restore'));
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
            'cust_list' => [ $cust->cust_id ],
        ];

        $response = $this->delete(route('customers.force-delete'), $data);
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
            'cust_list' => [ $cust->cust_id ],
        ];

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.force-delete'), $data);
        $response->assertStatus(403);
    }

    public function test_force_delete()
    {
        $cust = Customer::factory()->create();
        $cust->delete();
        $cust->save();
        $data = [
            'cust_list' => [ $cust->cust_id ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('customers.force-delete'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.delete'));
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
            'cust_list' => [ $cust->cust_id ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('customers.force-delete'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.delete'));
        $this->assertDatabaseMissing('customers', $cust->only(['cust_id']));
        $this->assertDatabaseMissing('file_uploads', $file->only(['file_id']));
    }
}
