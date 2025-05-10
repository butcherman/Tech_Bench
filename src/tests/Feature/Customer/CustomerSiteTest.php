<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerSite;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerSiteTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.sites.index', $customer->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.index', $customer->slug));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Customer/Site/Index')
                    ->has('permissions')
                    ->has('customer')
                    ->has('siteList')
                    ->has('alerts')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(
            route('customers.sites.create', $customer->slug)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_create_no_permission(): void
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

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.create', $customer->slug));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Customer/Site/Create')
                    ->has('default-state')
                    ->has('parent-customer')
            );
    }

    public function test_create_without_parent(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.create-site'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Customer/Site/Create')
                    ->has('default-state')
                    ->has('parent-customer')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
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

    public function test_store_no_permission(): void
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
        $this->changeRolePermission(4, 'Add Customer', false);

        $response = $this->actingAs($user)
            ->post(
                route('customers.sites.store', $customer->slug),
                $site->toArray()
            );

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;

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

    public function test_store_duplicate_slug(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;
        $site->site_name = $customer->Sites[0]->site_name;

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

    public function test_store_without_parent_in_url(): void
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

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
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

    public function test_show(): void
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
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Customer/Site/Show')
                    ->has('alerts')
                    ->has('availableEquipment')
                    ->has('customer')
                    ->has('currentSite')
                    ->has('isFav')
                    ->has('permissions')
                    ->has('phoneTypes')
                    ->has('fileTypes')
            );
    }

    public function test_show_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $site = CustomerSite::factory()->create();
        $invalid = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.show', [
                $invalid->slug,
                $site->site_slug,
            ]));

        $response->assertStatus(302)
            ->assertRedirect(route('customers.not-found'));
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
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

    public function test_edit_no_permission(): void
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

    public function test_edit(): void
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
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Customer/Site/Edit')
                    ->has('default-state')
                    ->has('parent-customer')
                    ->has('site')
            );
    }

    public function test_edit_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $site = CustomerSite::factory()->create();
        $invalid = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.sites.edit', [
                $invalid->slug,
                $site->site_slug,
            ]));

        $response->assertStatus(302)
            ->assertRedirect(route('customers.not-found'));
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
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

    public function test_update_no_permission(): void
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

    public function test_update(): void
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

    public function test_update_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $site = CustomerSite::factory()->create();
        $data = CustomerSite::factory()->make();
        $invalid = Customer::factory()->create();

        $data->cust_name = $site->Customer->name;
        $data->cust_id = $site->Customer->cust_id;

        $response = $this->actingAs($user)
            ->put(
                route('customers.sites.update', [
                    $invalid->slug,
                    $site->site_slug,
                ]),
                $data->toArray()
            );

        $response->assertStatus(302)
            ->assertRedirect(route('customers.not-found'));
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
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

    public function test_destroy_no_permission(): void
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

    public function test_destroy(): void
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

        $this->assertSoftDeleted(
            'customer_sites',
            $site->only(['cust_site_id'])
        );
    }

    public function test_destroy_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $site = CustomerSite::factory()->create();
        $data = ['reason' => 'For testing purposes'];
        $invalid = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('customers.sites.destroy', [
                $invalid->slug,
                $site->site_slug,
            ]), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('customers.not-found'));
    }
}
