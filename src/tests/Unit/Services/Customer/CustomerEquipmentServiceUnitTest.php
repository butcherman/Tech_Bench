<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use App\Models\CustomerVpn;
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

        $equipment->Sites()
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

    /*
    |---------------------------------------------------------------------------
    | restoreEquipment()
    |---------------------------------------------------------------------------
    */
    public function test_restore_equipment(): void
    {
        $equipment = CustomerEquipment::factory()->create();
        $equipment->delete();

        $testObj = new CustomerEquipmentService;
        $testObj->restoreEquipment($equipment);

        $this->assertDatabaseHas('customer_equipment', [
            'cust_equip_id' => $equipment->cust_equip_id,
            'deleted_at' => null,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | createCustomerVpnData()
    |---------------------------------------------------------------------------
    */
    public function test_create_customer_vpn_data(): void
    {
        $customer = Customer::factory()->create();
        $data = [
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ];

        $testObj = new CustomerEquipmentService;
        $res = $testObj->createCustomerVpnData(collect($data), $customer);

        $this->assertEquals($res->makeHidden(['vpn_id', 'created_at', 'updated_at'])->toArray(), $data);

        $this->assertDatabaseHas('customer_vpns', $data);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'vpn_id' => $res->vpn_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | shareCustomerVpnData()
    |---------------------------------------------------------------------------
    */
    public function test_share_vpn_data(): void
    {
        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $testObj = new CustomerEquipmentService;
        $testObj->shareCustomerVpnData($vpnData, $customer);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'vpn_id' => $vpnData->vpn_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateCustomerVpnData()
    |---------------------------------------------------------------------------
    */
    public function test_update_customer_vpn_data(): void
    {
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $data = [
            'vpn_client_name' => 'renamed client',
            'vpn_portal_url' => 'vpn.portal.random',
            'vpn_username' => 'usernameIsMy',
            'vpn_password' => 'passwordIsMy',
            'notes' => 'This is a test account',
        ];

        $testObj = new CustomerEquipmentService;
        $testObj->updateCustomerVpnData(collect($data), $vpnData);

        $this->assertDatabaseHas('customer_vpns', [
            'vpn_id' => $vpnData->vpn_id,
            'vpn_client_name' => 'renamed client',
            'vpn_portal_url' => 'vpn.portal.random',
            'vpn_username' => 'usernameIsMy',
            'vpn_password' => 'passwordIsMy',
            'notes' => 'This is a test account',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCustomerVpnData()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_customer_vpn_data(): void
    {
        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer->vpn_id = $vpnData->vpn_id;
        $customer->save();

        $testObj = new CustomerEquipmentService;
        $testObj->destroyCustomerVpnData($vpnData, $customer);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'vpn_id' => null,
        ]);

        $this->assertDatabaseMissing('customer_vpns', $vpnData->toArray());
    }

    public function test_destroy_customer_vpn_data_in_use(): void
    {
        $customer1 = Customer::factory()->create();
        $customer2 = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer1->vpn_id = $vpnData->vpn_id;
        $customer1->save();

        $customer2->vpn_id = $vpnData->vpn_id;
        $customer2->save();

        $testObj = new CustomerEquipmentService;
        $testObj->destroyCustomerVpnData($vpnData, $customer1);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer1->cust_id,
            'vpn_id' => null,
        ]);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer2->cust_id,
            'vpn_id' => $vpnData->vpn_id,
        ]);

        $this->assertDatabaseHas('customer_vpns', $vpnData->toArray());
    }
}
