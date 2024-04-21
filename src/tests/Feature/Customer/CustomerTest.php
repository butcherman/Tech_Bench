<?php

namespace Tests\Feature\Customer;

use App\Events\Customer\CustomerEvent;
use App\Models\Customer;
use App\Models\CustomerSite;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('customers.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('customers.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermission::whereRoleId(4)
            ->wherePermTypeId(7)
            ->update([
                'allow' => false,
            ]);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('customers.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $cust = Customer::factory()->make();
        $site = CustomerSite::factory()->make();

        $data = [
            'name' => $cust->name,
            'dba_name' => $cust->dba_name,
            'address' => $site->address,
            'city' => $site->city,
            'state' => $site->state,
            'zip' => $site->zip,
        ];

        $response = $this->post(route('customers.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertDatabaseMissing('customers', [
            'name' => $data['name'],
        ]);
    }

    public function test_store_no_permission()
    {
        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermission::whereRoleId(4)
            ->wherePermTypeId(7)
            ->update([
                'allow' => false,
            ]);
        $user = User::factory()->create();
        $cust = Customer::factory()->make();
        $site = CustomerSite::factory()->make();

        $data = [
            'name' => $cust->name,
            'dba_name' => $cust->dba_name,
            'address' => $site->address,
            'city' => $site->city,
            'state' => $site->state,
            'zip' => $site->zip,
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.store'), $data);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('customers', [
            'name' => $data['name'],
        ]);
    }

    public function test_store()
    {
        Event::fake();

        $cust = Customer::factory()->make();
        $site = CustomerSite::factory()->make();

        $data = [
            'name' => $cust->name,
            'dba_name' => $cust->dba_name,
            'address' => $site->address,
            'city' => $site->city,
            'state' => $site->state,
            'zip' => $site->zip,
        ];
        $slug = Str::slug($data['name']);

        $response = $this->ActingAs(User::factory()->create())
            ->post(route('customers.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.created', [
            'name' => $data['name'],
        ]));
        $response->assertRedirect(route('customers.show', $slug));

        $this->assertDatabaseHas('customers', [
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
        ]);
        $this->assertDatabaseHas('customer_sites', [
            'site_name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
        ]);

        Event::assertDispatched(CustomerEvent::class);
    }

    public function test_store_duplicate_slug()
    {
        Event::fake();

        $existing = Customer::factory()->create();
        $cust = Customer::factory()->make();
        $site = CustomerSite::factory()->make();

        $data = [
            'name' => $existing->name,
            'dba_name' => $cust->dba_name,
            'address' => $site->address,
            'city' => $site->city,
            'state' => $site->state,
            'zip' => $site->zip,
        ];
        $slug = Str::slug($data['name'].' 1');

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.created', [
            'name' => $data['name'],
        ]));
        $response->assertRedirect(route('customers.show', $slug));

        $this->assertDatabaseHas('customers', [
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
        ]);
        $this->assertDatabaseHas('customer_sites', [
            'site_name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
        ]);

        Event::assertDispatched(CustomerEvent::class);
    }

    public function test_store_second_duplicate_slug()
    {
        Event::fake();

        $existing1 = Customer::factory()->has(CustomerSite::factory())->create();
        Customer::factory()->create([
            'name' => $existing1->name,
            'slug' => Str::slug($existing1->slug.'-1'),
        ]);

        $cust = Customer::factory()->make();

        $data = [
            'name' => $existing1->name,
            'dba_name' => $cust->dba_name,
            'address' => $existing1->CustomerSite[0]->address,
            'city' => $existing1->CustomerSite[0]->city,
            'state' => $existing1->CustomerSite[0]->state,
            'zip' => $existing1->CustomerSite[0]->zip,
        ];
        $slug = Str::slug($data['name'].' 2');

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.created', [
            'name' => $data['name'],
        ]));
        $response->assertRedirect(route('customers.show', $slug));

        $this->assertDatabaseHas('customers', [
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
        ]);
        $this->assertDatabaseHas('customer_sites', [
            'site_name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
        ]);

        Event::assertDispatched(CustomerEvent::class);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $cust = Customer::factory()->create();

        $response = $this->get(route('customers.show', $cust->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.show', $cust->slug));
        $response->assertSuccessful();
    }

    public function test_show_multiple_sites()
    {
        $cust = Customer::factory()->create();
        CustomerSite::factory()->count(2)->create(['cust_id' => $cust->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.show', $cust->slug));
        $response->assertSuccessful();
    }

    public function test_show_customer_site()
    {
        $cust = Customer::factory()->create();
        $site = CustomerSite::factory()->create(['cust_id' => $cust->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.show', [$cust->slug, $site->slug]));
        $response->assertSuccessful();
    }

    public function test_show_invalid_customer()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.show', 'someRandomCustomerSlug'));
        $response->assertStatus(404);
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.edit', $customer->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $customer = Customer::factory()->create();
        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermission::whereRoleId(4)
            ->wherePermTypeId(8)
            ->update([
                'allow' => false,
            ]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 4]))
            ->get(route('customers.edit', $customer->slug));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.edit', $customer->slug));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $customer = Customer::factory()->create();
        $updated = Customer::factory()->make();

        $response = $this->put(route('customers.update', $customer->slug), $updated->only([
            'name',
            'dba_name',
            'primary_site_id',
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        //  Remove the "Update Customer" permission from the Tech Role
        UserRolePermission::whereRoleId(4)->wherePermTypeId(8)->update([
            'allow' => false,
        ]);
        $customer = Customer::factory()->create();
        $updated = Customer::factory()->make();

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.update', $customer->slug), $updated->only([
                'name',
                'dba_name',
                'primary_site_id',
            ]));
        $response->assertStatus(403);
    }

    public function test_update()
    {
        Event::fake();

        $customer = Customer::factory()->create();
        $newSite = CustomerSite::factory()->create([
            'cust_id' => $customer->cust_id,
        ]);
        $updated = Customer::factory()->make();

        $data = [
            'name' => $updated->name,
            'dba_name' => 'Some Business',
            'primary_site_id' => $newSite->cust_site_id,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.update', $customer->slug), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.updated', [
            'name' => $updated->name,
        ]));
        $response->assertRedirect(route('customers.show', $updated->slug));

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'name' => $updated->name,
        ]);

        Event::assertDispatched(CustomerEvent::class);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $cust = Customer::factory()->create();

        $response = $this->delete(route('customers.destroy', $cust->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.destroy', $cust->slug));
        $response->assertStatus(403);
        $this->assertDatabaseHas('customers', $cust->only(['cust_id', 'name']));
    }

    public function test_destroy_without_reason()
    {
        $cust = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('customers.destroy', $cust->slug));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['reason']);
    }

    public function test_destroy()
    {
        Event::fake();

        $cust = Customer::factory()->create();
        $data = ['reason' => 'Just because'];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('customers.destroy', $cust->slug), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('danger', __('cust.destroy', [
            'name' => $cust->name,
        ]));
        $response->assertRedirect(route('customers.index'));

        $this->assertSoftDeleted('customers', $cust->only(['cust_id']));

        Event::assertDispatched(CustomerEvent::class);
    }

    /**
     * Restore Method
     */
    public function test_restore_guest()
    {
        $cust = Customer::factory()->create();
        $cust->delete();

        $response = $this->get(
            route('customers.disabled.restore', $cust->cust_id)
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission()
    {
        $cust = Customer::factory()->create();
        $cust->delete();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.disabled.restore', $cust->cust_id));
        $response->assertStatus(403);
    }

    public function test_restore()
    {
        Event::fake();

        $cust = Customer::factory()->create();
        $cust->delete();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('customers.disabled.restore', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.restored', [
            'name' => $cust->name,
        ]));

        $this->assertDatabaseHas('customers', [
            'cust_id' => $cust->cust_id,
            'name' => $cust->name,
            'deleted_at' => null,
        ]);

        Event::assertDispatched(CustomerEvent::class);
    }

    /**
     * Force Delete Function
     */
    public function test_force_delete_guest()
    {
        $cust = Customer::factory()->create();
        $cust->delete();

        $response = $this->delete(
            route('customers.disabled.force-delete', $cust->cust_id)
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission()
    {
        $cust = Customer::factory()->create();
        $cust->delete();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.disabled.force-delete', $cust->cust_id));
        $response->assertStatus(403);
    }

    public function test_force_delete()
    {
        Event::fake();

        $cust = Customer::factory()->create();
        $cust->delete();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('customers.disabled.force-delete', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertSessionHas('danger', __('cust.force_deleted', [
            'name' => $cust->name,
        ]));

        $this->assertDatabaseMissing('customers', [
            'cust_id' => $cust->cust_id,
            'name' => $cust->name,
        ]);

        Event::assertDispatched(CustomerEvent::class);
    }
}
