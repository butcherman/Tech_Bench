<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use App\Models\EquipmentType;
use App\Services\Customer\CustomerEquipmentService;
use Tests\TestCase;

class CustomerEquipmentServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createEquipment()
    |---------------------------------------------------------------------------
    */
    public function test_create_equipment(): void
    {
        $equipment = EquipmentType::factory()->create();
        $customer = Customer::factory()->create();
        $siteList = CustomerSite::factory()
            ->count(4)
            ->create(['cust_id' => $customer->cust_id]);

        $data = [
            'equip_id' => $equipment->equip_id,
            'site_list' => [
                $siteList[0]->cust_site_id,
                $siteList[1]->cust_site_id,
            ],
        ];

        $testObj = new CustomerEquipmentService;
        $res = $testObj->createEquipment(collect($data), $customer);

        $this->assertEquals($res->equip_id, $equipment->equip_id);

        $this->assertDatabaseHas('customer_equipment', [
            'equip_id' => $equipment->equip_id,
            'cust_id' => $customer->cust_id,
        ]);

        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_site_id' => $siteList[0]->cust_site_id,
            'cust_equip_id' => $res->cust_equip_id,
        ]);

        $this->assertDatabaseMissing('customer_site_equipment', [
            'cust_site_id' => $siteList[2]->cust_site_id,
            'cust_equip_id' => $res->cust_equip_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateEquipmentSites()
    |---------------------------------------------------------------------------
    */
    public function test_update_equipment_sites(): void
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $siteList = CustomerSite::factory()
            ->count(4)
            ->create(['cust_id' => $customer->cust_id]);

        $equipment->CustomerSite()
            ->sync([$siteList[0]->cust_site_id, $siteList[2]->cust_site_id]);

        $data = [
            'site_list' => [
                $siteList[0]->cust_site_id,
                $siteList[1]->cust_site_id,
            ],
        ];

        $testObj = new CustomerEquipmentService;
        $testObj->updateEquipmentSites(collect($data), $equipment);

        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_site_id' => $siteList[0]->cust_site_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_site_id' => $siteList[1]->cust_site_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $this->assertDatabaseMissing('customer_site_equipment', [
            'cust_site_id' => $siteList[2]->cust_site_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyEquipment()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_equipment(): void
    {
        $equipment = CustomerEquipment::factory()->create();

        $testObj = new CustomerEquipmentService;
        $testObj->destroyEquipment($equipment);

        $this->assertSoftDeleted($equipment);
    }

    public function test_destroy_equipment_force(): void
    {
        $equipment = CustomerEquipment::factory()->create();

        $testObj = new CustomerEquipmentService;
        $testObj->destroyEquipment($equipment, true);

        $this->assertDatabaseMissing('customer_equipment', [
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);
    }
}
