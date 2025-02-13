<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerSite;
use App\Models\User;
use App\Models\UserRolePermission;
use Inertia\Testing\AssertableInertia as Assert;
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.index', $customer->slug));
        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Site/Index')
                ->has('permissions')
                ->has('customer')
                ->has('siteList')
                ->has('alerts')
            );
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.sites.create', $customer->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        //  Remove the "Add Customer" permission from the Tech Role
        $this->changeRolePermission(4, 'Add Customer', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.create', $customer->slug));

        $response->assertForbidden();
    }

    public function test_create()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.create', $customer->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Site/Create')
                ->has('default-state')
                ->has('parent-customer')
            );
    }

    public function test_create_without_parent()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.create-site'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Site/Create')
                ->has('default-state')
                ->has('parent-customer')
            );
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        //  Remove the "Add Customer" permission from the Tech Role
        $this->changeRolePermission(4, 'Add Customer', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
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

        $response = $this->actingAs($user)
            ->post(
                route('customers.sites.store', $customer->slug),
                $site->toArray()
            );

        $response->assertForbidden();
    }

    public function test_store()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;

        $response = $this->actingAs($user)
            ->post(route('customers.sites.store', $customer->slug), $site->toArray());

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.site.created', [
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

    public function test_store_duplicate_slug()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;
        $site->site_name = $customer->CustomerSite[0]->site_name;

        $response = $this->actingAs($user)
            ->post(
                route('customers.sites.store', $customer->slug),
                $site->toArray()
            );

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.site.created', [
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
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;

        $response = $this->actingAs($user)
            ->post(route('customers.store-site'), $site->toArray());

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.site.created', [
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $site = CustomerSite::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.show', [
                $site->Customer->slug,
                $site->site_slug,
            ]));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Site/Show')
                ->has('permissions')
                ->has('customer')
                ->has('site')
                ->has('siteList')
                ->has('alerts')
                ->has('equipmentList')
                ->has('contacts')
                ->has('notes')
                ->has('files')
                ->has('is-fav')
            );
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        //  Remove the "Update Customer" permission from the Tech Role
        $this->changeRolePermission(4, 'Update Customer', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $site = CustomerSite::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.edit', [
                $site->Customer->slug,
                $site->site_slug,
            ]));

        $response->assertForbidden();
    }

    public function test_edit()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $site = CustomerSite::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.edit', [
                $site->Customer->slug,
                $site->site_slug,
            ]));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Site/Edit')
                ->has('default-state')
                ->has('parent-customer')
                ->has('site')
            );
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        //  Remove the "Update Customer" permission from the Tech Role
        $this->changeRolePermission(4, 'Update Customer', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $site = CustomerSite::factory()->create();
        $data = CustomerSite::factory()->make();

        $data->cust_name = $site->Customer->name;
        $data->cust_id = $site->Customer->cust_id;

        $response = $this->actingAs($user)
            ->put(
                route('customers.sites.update', [
                    $site->Customer->slug,
                    $site->site_slug,
                ]),
                $data->toArray()
            );

        $response->assertForbidden();
    }

    public function test_update()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $site = CustomerSite::factory()->create();
        $data = CustomerSite::factory()->make();

        $data->cust_name = $site->Customer->name;
        $data->cust_id = $site->Customer->cust_id;

        $response = $this->actingAs($user)
            ->put(
                route('customers.sites.update', [
                    $site->Customer->slug,
                    $site->site_slug,
                ]),
                $data->toArray()
            );

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.site.updated', [
                'name' => $data->site_name,
            ]));

        $this->assertDatabaseHas('customer_sites', [
            'cust_site_id' => $site->cust_site_id,
            'site_name' => $data->site_name,
        ]);
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        //  Remove the "Deactivate Customer" permission from the Tech Role
        $this->changeRolePermission(4, 'Deactivate Customer', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $site = CustomerSite::factory()->create();
        $data = ['reason' => 'For testing purposes'];

        $response = $this->actingAs($user)
            ->delete(route('customers.sites.destroy', [
                $site->Customer->slug,
                $site->site_slug,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_destroy()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $site = CustomerSite::factory()->create();
        $data = ['reason' => 'For testing purposes'];

        $response = $this->actingAs($user)
            ->delete(route('customers.sites.destroy', [
                $site->Customer->slug,
                $site->site_slug,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('danger', __('cust.destroy', [
                'name' => $site->site_name,
            ]));

        $this->assertSoftDeleted('customer_sites', $site->only(['cust_site_id']));
    }
}
