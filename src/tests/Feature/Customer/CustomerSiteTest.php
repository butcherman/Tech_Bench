<?php

namespace Tests\Feature\Customer;

use App\Events\Customer\CustomerSiteEvent;
use App\Models\Customer;
use App\Models\CustomerSite;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CustomerSiteTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.sites.index', $customer->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.sites.index', $customer->slug));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.sites.create', $customer->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $customer = Customer::factory()->create();
        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermission::whereRoleId(4)
            ->wherePermTypeId(7)
            ->update([
                'allow' => false,
            ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.sites.create', $customer->slug));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.sites.create', $customer->slug));
        $response->assertSuccessful();
    }

    public function test_create_without_parent()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.create-site'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;

        $response = $this->post(
            route('customers.sites.store', $customer->slug),
            $site->toArray()
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;

        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermission::whereRoleId(4)
            ->wherePermTypeId(7)
            ->update([
                'allow' => false,
            ]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.sites.store', $customer->slug), $site->toArray());
        $response->assertStatus(403);
    }

    public function test_store()
    {
        Event::fake();

        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.sites.store', $customer->slug), $site->toArray());
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.site.created', [
            'name' => $site->site_name,
        ]));

        $this->assertDatabaseHas('customer_sites', $site->only([
            'cust_id',
            'site_name',
            'address',
            'city',
            'state',
            'zip',
        ]));

        Event::assertDispatched(CustomerSiteEvent::class);
    }

    public function test_store_duplicate_slug()
    {
        Event::fake();

        $customer = Customer::factory()->create();
        CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;
        $site->site_name = $customer->CustomerSite[0]->site_name;

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.sites.store', $customer->slug), $site->toArray());
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.site.created', [
            'name' => $site->site_name,
        ]));

        $this->assertDatabaseHas('customer_sites', $site->only([
            'cust_id',
            'site_name',
            'address',
            'city',
            'state',
            'zip',
        ]));
    }

    public function test_store_without_parent_in_url()
    {
        Event::fake();

        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.store-site'), $site->toArray());
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.site.created', [
            'name' => $site->site_name,
        ]));

        $this->assertDatabaseHas('customer_sites', $site->only([
            'cust_id',
            'site_name',
            'address',
            'city',
            'state',
            'zip',
        ]));

        Event::assertDispatched(CustomerSiteEvent::class);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $site = CustomerSite::factory()->create();

        $response = $this->get(route('customers.sites.show', [
            $site->Customer->slug,
            $site->site_slug,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $site = CustomerSite::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.sites.show', [
                $site->Customer->slug,
                $site->site_slug,
            ]));
        $response->assertSuccessful();
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $site = CustomerSite::factory()->create();

        $response = $this->get(route('customers.sites.edit', [
            $site->Customer->slug,
            $site->site_slug,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $site = CustomerSite::factory()->create();

        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermission::whereRoleId(4)
            ->wherePermTypeId(8)
            ->update([
                'allow' => false,
            ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.sites.edit', [
                $site->Customer->slug,
                $site->site_slug,
            ]));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $site = CustomerSite::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.sites.edit', [
                $site->Customer->slug,
                $site->site_slug,
            ]));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $site = CustomerSite::factory()->create();
        $data = CustomerSite::factory()->make();

        $data->cust_name = $site->Customer->name;
        $data->cust_id = $site->Customer->cust_id;

        $response = $this->put(
            route('customers.sites.update', [
                $site->Customer->slug,
                $site->site_slug,
            ]),
            $data->toArray()
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $site = CustomerSite::factory()->create();
        $data = CustomerSite::factory()->make();

        $data->cust_name = $site->Customer->name;
        $data->cust_id = $site->Customer->cust_id;

        //  Remove the "Add Customer" permission from the Tech Role
        UserRolePermission::whereRoleId(4)
            ->wherePermTypeId(8)
            ->update([
                'allow' => false,
            ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(
                route('customers.sites.update', [
                    $site->Customer->slug,
                    $site->site_slug,
                ]),
                $data->toArray()
            );
        $response->assertStatus(403);
    }

    public function test_update()
    {
        Event::fake();

        $site = CustomerSite::factory()->create();
        $data = CustomerSite::factory()->make();

        $data->cust_name = $site->Customer->name;
        $data->cust_id = $site->Customer->cust_id;

        $response = $this->actingAs(User::factory()->create())
            ->put(
                route('customers.sites.update', [
                    $site->Customer->slug,
                    $site->site_slug,
                ]),
                $data->toArray()
            );
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.site.updated', [
            'name' => $data->site_name,
        ]));

        $this->assertDatabaseHas('customer_sites', [
            'cust_site_id' => $site->cust_site_id,
            'site_name' => $data->site_name,
        ]);

        Event::assertDispatched(CustomerSiteEvent::class);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $site = CustomerSite::factory()->create();
        $data = ['reason' => 'For testing purposes'];

        $response = $this->delete(route('customers.sites.destroy', [
            $site->Customer->slug,
            $site->site_slug,
        ]), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $site = CustomerSite::factory()->create();
        $data = ['reason' => 'For testing purposes'];

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.sites.destroy', [
                $site->Customer->slug,
                $site->site_slug,
            ]), $data);
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        Event::fake();

        $site = CustomerSite::factory()->create();
        $data = ['reason' => 'For testing purposes'];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('customers.sites.destroy', [
                $site->Customer->slug,
                $site->site_slug,
            ]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('danger', __('cust.destroy', ['name' => $site->site_name]));

        $this->assertSoftDeleted('customer_sites', $site->only(['cust_site_id']));

        Event::assertDispatched(CustomerSiteEvent::class);
    }
}
