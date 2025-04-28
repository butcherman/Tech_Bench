<?php

namespace Tests\Feature\Customer;

use App\Jobs\Customer\CreateCustomerDataFieldsJob;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use App\Models\DataField;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerEquipmentTest extends TestCase
{
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()
            ->has(CustomerSite::factory()->count(2), 'sites')
            ->create();

        $this->customer->primary_site_id = $this->customer
            ->Sites[0]
            ->cust_site_id;

        $this->customer->save();
    }

    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(
            route('customers.equipment.index', $this->customer->slug)
        );
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.index', $this->customer->slug));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Customer/Equipment/Index')
                    ->has('permissions')
                    ->has('customer')
                    ->has('siteList')
                    ->has('alerts')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $data = [
            'equip_id' => EquipmentType::factory()->create()->equip_id,
            'site_list' => $this->customer->Sites->toArray(),
        ];

        $response = $this->post(
            route('customers.equipment.store', $this->customer->slug),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        // Remove the 'Add Customer Equipment' permission from the Tech Role
        $this->changeRolePermission(4, 'Add Customer Equipment', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'equip_id' => EquipmentType::factory()->createQuietly()->equip_id,
            'site_list' => $this->customer->Sites->toArray(),
        ];

        $response = $this->actingAs($user)
            ->post(
                route('customers.equipment.store', $this->customer->slug),
                $data
            );
        $response->assertForbidden();
    }

    public function test_store(): void
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $equip = EquipmentType::factory()->createQuietly();
        DataField::factory()
            ->count(2)
            ->createQuietly(['equip_id' => $equip->equip_id]);

        $data = [
            'equip_id' => $equip->equip_id,
            'site_list' => $this->customer
                ->Sites
                ->pluck('cust_site_id')
                ->toArray(),
        ];

        $response = $this->actingAs($user)
            ->post(
                route('customers.equipment.store', $this->customer->slug),
                $data
            );

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.equipment.created', [
                'equip' => $equip->name,
            ]));

        $this->assertDatabaseHas('customer_equipment', [
            'cust_id' => $this->customer->cust_id,
            'equip_id' => $equip->equip_id,
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->Equipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][0],
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->Equipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][1],
        ]);

        Bus::assertDispatched(CreateCustomerDataFieldsJob::class);
    }

    public function test_store_duplicate_equipment(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $siteList = $this->customer
            ->Sites
            ->pluck('cust_site_id')
            ->toArray();

        $equip = EquipmentType::factory()->createQuietly();
        DataField::factory()->count(2)->createQuietly(['equip_id' => $equip->equip_id]);

        $custEquipment = CustomerEquipment::create([
            'cust_id' => $this->customer->cust_id,
            'equip_id' => $equip->equip_id,
        ]);
        $custEquipment->CustomerSite()->sync($siteList);

        $data = [
            'equip_id' => $equip->equip_id,
            'site_list' => $siteList,
        ];

        $response = $this->actingAs($user)
            ->post(
                route('customers.equipment.store', $this->customer->slug),
                $data
            );

        $response->assertStatus(302)
            ->assertSessionHasErrors(['site_list']);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);

        $response = $this->get(route('customers.equipment.show', [
            $this->customer->slug,
            $equip->cust_equip_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.show', [
                $this->customer->slug,
                $equip->cust_equip_id,
            ]));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Customer/Equipment/Show')
                    ->has('permissions')
                    ->has('customer')
                    ->has('equipment')
                    ->has('siteList')
                    ->has('equipment-data')
                // ->has('notes')
                // ->has('files')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);
        $newSites = CustomerSite::factory()
            ->count(2)
            ->create(['cust_id' => $this->customer->cust_id])
            ->pluck('cust_site_id')
            ->toArray();

        $data = [
            'site_list' => [
                $this->customer->Sites[0]->cust_site_id,
                $newSites[0],
                $newSites[1],
            ],
        ];

        $response = $this->put(route('customers.equipment.update', [
            $this->customer->slug,
            $equip->cust_equip_id,
        ]), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        // Remove the 'Edit Customer Equipment' permission from the Tech Role
        $this->changeRolePermission(4, 'Edit Customer Equipment', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);
        $newSites = CustomerSite::factory()
            ->count(2)
            ->create(['cust_id' => $this->customer->cust_id])
            ->pluck('cust_site_id')
            ->toArray();

        $data = [
            'site_list' => [
                $this->customer->Sites[0]->cust_site_id,
                $newSites[0],
                $newSites[1],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.equipment.update', [
                $this->customer->slug,
                $equip->cust_equip_id,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);
        $newSites = CustomerSite::factory()
            ->count(2)
            ->create(['cust_id' => $this->customer->cust_id])
            ->pluck('cust_site_id')
            ->toArray();

        $data = [
            'site_list' => [
                $this->customer->Sites[0]->cust_site_id,
                $newSites[0],
                $newSites[1],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.equipment.update', [
                $this->customer->slug,
                $equip->cust_equip_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.equipment.site-updated'));

        $this->assertDatabaseHas('customer_equipment', [
            'cust_id' => $this->customer->cust_id,
            'equip_id' => $equip->equip_id,
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->Equipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][0],
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->Equipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][1],
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->Equipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][2],
        ]);
        $this->assertDatabaseMissing('customer_site_equipment', [
            'cust_equip_id' => $this->customer->Equipment[0]->cust_equip_id,
            'cust_site_id' => $this->customer->Sites[1]->cust_site_id,
        ]);
    }

    public function test_update_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $invalid = Customer::factory()->create();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);
        $newSites = CustomerSite::factory()
            ->count(2)
            ->create(['cust_id' => $this->customer->cust_id])
            ->pluck('cust_site_id')
            ->toArray();

        $data = [
            'site_list' => [
                $this->customer->Sites[0]->cust_site_id,
                $newSites[0],
                $newSites[1],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.equipment.update', [
                $invalid->slug,
                $equip->cust_equip_id,
            ]), $data);

        $response->assertStatus(404);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);

        $response = $this->delete(route('customers.equipment.destroy', [
            $this->customer->slug,
            $equip->cust_equip_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('customers.equipment.destroy', [
                $this->customer->slug,
                $equip->cust_equip_id,
            ]));
        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('customers.equipment.destroy', [
                $this->customer->slug,
                $equip->cust_equip_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'Equipment Deleted');

        $this->assertSoftDeleted('customer_equipment', [
            'cust_equip_id' => $equip->cust_equip_id,
        ]);
    }

    public function test_destroy_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $invalid = Customer::factory()->create();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('customers.equipment.destroy', [
                $invalid->slug,
                $equip->cust_equip_id,
            ]));

        $response->assertStatus(404);
    }

    /*
    |---------------------------------------------------------------------------
    | Restore Method
    |---------------------------------------------------------------------------
    */
    // public function test_restore_guest(): void
    // {
    //     $equip = CustomerEquipment::factory()->create([
    //         'cust_id' => $this->customer->cust_id,
    //     ]);

    //     $response = $this->get(route('customers.deleted-items.restore.equipment', [
    //         $equip->cust_id,
    //         $equip->cust_equip_id,
    //     ]));

    //     $response->assertStatus(302)
    //         ->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_restore_no_permission(): void
    // {
    //     /** @var User $user */
    //     $user = User::factory()->createQuietly();
    //     $equip = CustomerEquipment::factory()->create([
    //         'cust_id' => $this->customer->cust_id,
    //     ]);

    //     $response = $this->actingAs($user)
    //         ->get(route('customers.deleted-items.restore.equipment', [
    //             $equip->cust_id,
    //             $equip->cust_equip_id,
    //         ]));

    //     $response->assertForbidden();
    // }

    // public function test_restore(): void
    // {
    //     /** @var User $user */
    //     $user = User::factory()->createQuietly(['role_id' => 1]);
    //     $equip = CustomerEquipment::factory()->create([
    //         'cust_id' => $this->customer->cust_id,
    //     ]);

    //     $response = $this->actingAs($user)
    //         ->get(route('customers.deleted-items.restore.equipment', [
    //             $equip->cust_id,
    //             $equip->cust_equip_id,
    //         ]));

    //     $response->assertStatus(302)
    //         ->assertSessionHas('success', __('cust.equipment.restored', [
    //             'equip' => $equip->equip_name,
    //         ]));

    //     $this->assertDatabaseHas('customer_equipment', $equip->only([
    //         'cust_equip_id',
    //     ]));
    // }

    /**
     * Force Delete Method
     */
    // public function test_force_delete_guest(): void
    // {
    //     $equip = CustomerEquipment::factory()->create([
    //         'cust_id' => $this->customer->cust_id,
    //     ]);
    //     $equip->delete();

    //     $response = $this->delete(route('customers.deleted-items.force-delete.equipment', [
    //         $equip->cust_id,
    //         $equip->cust_equip_id,
    //     ]));

    //     $response->assertStatus(302)
    //         ->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_force_delete_no_permission(): void
    // {
    //     /** @var User $user */
    //     $user = User::factory()->createQuietly();
    //     $equip = CustomerEquipment::factory()->create([
    //         'cust_id' => $this->customer->cust_id,
    //     ]);
    //     $equip->delete();

    //     $response = $this->actingAs($user)
    //         ->delete(route('customers.deleted-items.force-delete.equipment', [
    //             $equip->cust_id,
    //             $equip->cust_equip_id,
    //         ]));

    //     $response->assertForbidden();
    // }

    // public function test_force_delete(): void
    // {
    //     /** @var User $user */
    //     $user = User::factory()->createQuietly(['role_id' => 1]);
    //     $equip = CustomerEquipment::factory()->create([
    //         'cust_id' => $this->customer->cust_id,
    //     ]);
    //     $equip->delete();

    //     $response = $this->actingAs($user)
    //         ->delete(route('customers.deleted-items.force-delete.equipment', [
    //             $equip->cust_id,
    //             $equip->cust_equip_id,
    //         ]));

    //     $response->assertStatus(302)
    //         ->assertSessionHas('warning', __('cust.equipment.force_deleted', [
    //             'equip' => $equip->equip_name,
    //         ]));

    //     $this->assertDatabaseMissing('customer_equipment', $equip->only([
    //         'cust_equip_id',
    //     ]));
    // }
}
