<?php

namespace Tests\Feature\Customer;

use App\Events\Customer\CustomerSlugChangedEvent;
use App\Exceptions\Customer\CustomerNotFoundException;
use App\Jobs\Customer\ForceDeleteCustomerJob;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use App\Services\Customer\CustomerAdministrationService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery\MockInterface;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('customers.index'));
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Index')
                ->has('permissions'));
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $response = $this->get(route('customers.create'));
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

        $response = $this->actingAs($user)->get(route('customers.create'));
        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.create'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Create')
                ->has('select-id')
                ->has('default-state'));
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();

        $this->assertDatabaseMissing('customers', [
            'name' => $data['name'],
        ]);
    }

    public function test_store_no_permission(): void
    {
        //  Remove the "Add Customer" permission from the Tech Role
        $this->changeRolePermission(4, 'Add Customer', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
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

        $response->assertForbidden();

        $this->assertDatabaseMissing('customers', [
            'name' => $data['name'],
        ]);
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
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

        $response = $this->ActingAs($user)
            ->post(route('customers.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.created', [
                'name' => $data['name'],
            ]))
            ->assertRedirect(route('customers.show', $slug));

        $this->assertDatabaseHas('customers', [
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
        ]);
        $this->assertDatabaseHas('customer_sites', [
            'site_name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $cust = Customer::factory()->create();

        $response = $this->get(route('customers.show', $cust->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_single_site(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cust = Customer::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.show', $cust->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Site/Show')
                ->has('permissions')
                ->has('customer')
                ->has('currentSite')
                ->has('alerts')
                ->has('isFav'));
    }

    public function test_show_multiple_sites(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cust = Customer::factory()->createQuietly();
        CustomerSite::factory()->count(2)->createQuietly(['cust_id' => $cust->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.show', $cust->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Show')
                ->has('permissions')
                ->has('customer')
                ->has('siteList')
                ->has('alerts')
                ->has('isFav'));
    }

    public function test_show_invalid_customer(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $this->expectException(CustomerNotFoundException::class);

        $response = $this->withoutExceptionHandling()
            ->actingAs($user)
            ->get(route('customers.show', 'someRandomCustomerSlug'));

        $response->assertStatus(302)
            ->assertRedirect(route('customers.not-found'));

        Exceptions::assertReported(CustomerNotFoundException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.edit', $customer->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        //  Remove the "Update Customer" permission from the Tech Role
        $this->changeRolePermission(4, 'Update Customer', false);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 4]);
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.edit', $customer->slug));
        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.edit', $customer->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Edit')
                ->has('selectId')
                ->has('default-state')
                ->has('customer')
                ->has('siteList'));
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $customer = Customer::factory()->createQuietly();
        $updated = Customer::factory()->make();

        $response = $this->put(route('customers.update', $customer->slug), $updated->only([
            'name',
            'dba_name',
            'primary_site_id',
        ]));

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
        $customer = Customer::factory()->createQuietly();
        $updated = Customer::factory()->make();

        $response = $this->actingAs($user)
            ->put(route('customers.update', $customer->slug), $updated->only([
                'name',
                'dba_name',
                'primary_site_id',
            ]));

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->createQuietly();
        $newSite = CustomerSite::factory()->createQuietly([
            'cust_id' => $customer->cust_id,
        ]);
        $updated = Customer::factory()->make();

        $data = [
            'name' => $updated->name,
            'dba_name' => 'Some Business',
            'primary_site_id' => $newSite->cust_site_id,
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.update', $customer->slug), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.updated', [
                'name' => $updated->name,
            ]))
            ->assertRedirect(route('customers.show', $updated->slug));

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'name' => $updated->name,
        ]);

        Event::assertDispatched(CustomerSlugChangedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $cust = Customer::factory()->createQuietly();

        $response = $this->delete(route('customers.destroy', $cust->slug));
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cust = Customer::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('customers.destroy', $cust->slug));
        $response->assertForbidden();

        $this->assertDatabaseHas('customers', $cust->only(['cust_id', 'name']));
    }

    public function test_destroy_without_reason(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $cust = Customer::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('customers.destroy', $cust->slug));

        $response->assertStatus(302)
            ->assertSessionHasErrorsIn('form_error', ['reason']);
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $cust = Customer::factory()->createQuietly();
        $data = ['reason' => 'Just because'];

        $response = $this->actingAs($user)
            ->delete(route('customers.destroy', $cust->slug), $data);

        $response->assertStatus(302)
            ->assertSessionHas('danger', __('cust.destroy', [
                'name' => $cust->name,
            ]))
            ->assertRedirect(route('customers.index'));

        $this->assertSoftDeleted('customers', $cust->only(['cust_id']));
    }

    /**
     * Restore Method
     */
    public function test_restore_guest(): void
    {
        $cust = Customer::factory()->createQuietly();
        $cust->delete();

        $response = $this->get(
            route('customers.disabled.restore', $cust->slug)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cust = Customer::factory()->createQuietly();
        $cust->delete();

        $response = $this->actingAs($user)
            ->get(route('customers.disabled.restore', $cust->slug));

        $response->assertForbidden();
    }

    public function test_restore(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $cust = Customer::factory()->createQuietly();
        $cust->delete();

        $response = $this->actingAs($user)
            ->get(route('customers.disabled.restore', $cust->slug));

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.restored', [
                'name' => $cust->name,
            ]));

        $this->assertDatabaseHas('customers', [
            'cust_id' => $cust->cust_id,
            'name' => $cust->name,
            'deleted_at' => null,
        ]);
    }

    /**
     * Force Delete Function
     */
    public function test_force_delete_guest(): void
    {
        $cust = Customer::factory()->createQuietly();
        $cust->delete();

        $response = $this->delete(
            route('customers.disabled.force-delete', $cust->slug)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cust = Customer::factory()->createQuietly();
        $cust->delete();

        $response = $this->actingAs($user)
            ->delete(route('customers.disabled.force-delete', $cust->slug));

        $response->assertForbidden();
    }

    public function test_force_delete(): void
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $cust = Customer::factory()
            ->has(CustomerFile::factory(), 'files')
            ->has(CustomerNote::factory(), 'notes')
            ->has(CustomerContact::factory(), 'contacts')
            ->createQuietly();
        $cust->delete();

        $this->mock(CustomerAdministrationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('addToWorkingJobs')->once()->with(Customer::class);
        });

        $response = $this->actingAs($user)
            ->delete(route('customers.disabled.force-delete', $cust->slug));

        $response->assertStatus(302)
            ->assertSessionHas('danger', __('cust.force_deleted', [
                'name' => $cust->name,
            ]));

        Bus::assertDispatched(ForceDeleteCustomerJob::class);
    }
}
