<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use App\Models\EquipmentType;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerEquipmentTest extends TestCase
{
    protected $customer;

    public function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()
            ->has(CustomerSite::factory()->count(2))
            ->create();
        $this->customer->primary_site_id = $this->customer
            ->CustomerSite[0]
            ->cust_site_id;
        $this->customer->save();
    }

    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(
            route('customers.equipment.index', $this->customer->slug)
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.equipment.index', $this->customer->slug));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'equip_id' => EquipmentType::factory()->create()->equip_id,
            'site_list' => $this->customer->CustomerSite->toArray(),
        ];

        $response = $this->post(
            route('customers.equipment.store', $this->customer->slug),
            $data
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        // Remove the 'Add Customer Equipment' permission from the Tech Role
        UserRolePermission::whereRoleId(4)
            ->wherePermTypeId(11)
            ->update([
                'allow' => false,
            ]);

        $data = [
            'equip_id' => EquipmentType::factory()->create()->equip_id,
            'site_list' => $this->customer->CustomerSite->toArray(),
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(
                route('customers.equipment.store', $this->customer->slug),
                $data
            );
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $equip = EquipmentType::factory()->create();
        $data = [
            'equip_id' => $equip->equip_id,
            'site_list' => $this->customer
                ->CustomerSite
                ->pluck('cust_site_id')
                ->toArray(),
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(
                route('customers.equipment.store', $this->customer->slug),
                $data
            );

        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.equipment.created', [
            'equip' => $equip->name
        ]));

        $this->assertDatabaseHas('customer_equipment', [
            'cust_id' => $this->customer->cust_id,
            'equip_id' => $equip->equip_id,
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][0],
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][1],
        ]);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id
        ]);

        $response = $this->get(route('customers.equipment.show', [
            $this->customer->slug,
            $equip->cust_equip_id
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.equipment.show', [
                $this->customer->slug,
                $equip->cust_equip_id
            ]));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id
        ]);
        $newSites = CustomerSite::factory()
            ->count(2)
            ->create(['cust_id' => $this->customer->cust_id])
            ->pluck('cust_site_id')
            ->toArray();

        $data = [
            'site_list' => [
                $this->customer->CustomerSite[0]->cust_site_id,
                $newSites[0],
                $newSites[1]
            ]
        ];

        $response = $this->put(route('customers.equipment.update', [
            $this->customer->slug,
            $equip->cust_equip_id
        ]), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        // Remove the 'Edit Customer Equipment' permission from the Tech Role
        UserRolePermission::whereRoleId(4)
            ->wherePermTypeId(12)
            ->update([
                'allow' => false,
            ]);

        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id
        ]);
        $newSites = CustomerSite::factory()
            ->count(2)
            ->create(['cust_id' => $this->customer->cust_id])
            ->pluck('cust_site_id')
            ->toArray();

        $data = [
            'site_list' => [
                $this->customer->CustomerSite[0]->cust_site_id,
                $newSites[0],
                $newSites[1]
            ]
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.equipment.update', [
                $this->customer->slug,
                $equip->cust_equip_id
            ]), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id
        ]);
        $newSites = CustomerSite::factory()
            ->count(2)
            ->create(['cust_id' => $this->customer->cust_id])
            ->pluck('cust_site_id')
            ->toArray();

        $data = [
            'site_list' => [
                $this->customer->CustomerSite[0]->cust_site_id,
                $newSites[0],
                $newSites[1]
            ]
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.equipment.update', [
                $this->customer->slug,
                $equip->cust_equip_id
            ]), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('customer_equipment', [
            'cust_id' => $this->customer->cust_id,
            'equip_id' => $equip->equip_id,
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][0],
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][1],
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $this->customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][2],
        ]);
        $this->assertDatabaseMissing('customer_site_equipment', [
            'cust_equip_id' => $this->customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $this->customer->CustomerSite[1]->cust_site_id,
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id
        ]);

        $response = $this->delete(route('customers.equipment.destroy', [
            $this->customer->slug,
            $equip->cust_equip_id
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.equipment.destroy', [
                $this->customer->slug,
                $equip->cust_equip_id
            ]));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id
        ]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('customers.equipment.destroy', [
                $this->customer->slug,
                $equip->cust_equip_id
            ]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.equipment.deleted', [
            'equip' => $equip->equip_name
        ]));

        $this->assertSoftDeleted('customer_equipment', [
            'cust_equip_id' => $equip->cust_equip_id,
        ]);
    }
}
