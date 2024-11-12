<?php

namespace Tests\Unit\Services\Customer;

use App\Jobs\Customer\CreateCustomerDataFieldsJob;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use App\Models\DataField;
use App\Models\EquipmentType;
use App\Services\Customer\CustomerEquipmentService;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CustomerEquipmentUnitTest extends TestCase
{
    public function test_create_equipment(): void
    {
        Bus::fake();

        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(2))
            ->create();
        $equip = EquipmentType::factory()->createQuietly();
        DataField::factory()
            ->count(2)
            ->createQuietly(['equip_id' => $equip->equip_id]);

        $data = [
            'equip_id' => $equip->equip_id,
            'site_list' => $customer
                ->CustomerSite
                ->pluck('cust_site_id')
                ->toArray(),
        ];

        $testObj = new CustomerEquipmentService;
        $res = $testObj->createEquipment(collect($data), $customer);

        $this->assertEquals($data['equip_id'], $res->equip_id);

        $this->assertDatabaseHas('customer_equipment', [
            'cust_id' => $customer->cust_id,
            'equip_id' => $equip->equip_id,
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][0],
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][1],
        ]);

        Bus::assertDispatched(CreateCustomerDataFieldsJob::class);
    }

    public function test_update_equipment_sites(): void
    {
        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(2))
            ->create();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $customer->cust_id,
        ]);
        $newSites = CustomerSite::factory()
            ->count(2)
            ->create(['cust_id' => $customer->cust_id])
            ->pluck('cust_site_id')
            ->toArray();

        $data = [
            'site_list' => [
                $customer->CustomerSite[0]->cust_site_id,
                $newSites[0],
                $newSites[1],
            ],
        ];

        $testObj = new CustomerEquipmentService;
        $testObj->updateEquipmentSites(collect($data), $equip);

        $this->assertDatabaseHas('customer_equipment', [
            'cust_id' => $customer->cust_id,
            'equip_id' => $equip->equip_id,
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][0],
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][1],
        ]);
        $this->assertDatabaseHas('customer_site_equipment', [
            'cust_equip_id' => $customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $data['site_list'][2],
        ]);
        $this->assertDatabaseMissing('customer_site_equipment', [
            'cust_equip_id' => $customer->CustomerEquipment[0]->cust_equip_id,
            'cust_site_id' => $customer->CustomerSite[1]->cust_site_id,
        ]);
    }

    public function test_destroy_equipment(): void
    {
        $customer = Customer::factory()->create();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $customer->cust_id,
        ]);

        $testObj = new CustomerEquipmentService;
        $testObj->destroyEquipment($equip);

        $this->assertSoftDeleted('customer_equipment', [
            'cust_equip_id' => $equip->cust_equip_id,
        ]);
    }

    public function test_destroy_equipment_force(): void
    {
        $customer = Customer::factory()->create();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $customer->cust_id,
        ]);

        $testObj = new CustomerEquipmentService;
        $testObj->destroyEquipment($equip, true);

        $this->assertDatabaseMissing('customer_equipment', [
            'cust_equip_id' => $equip->cust_equip_id,
        ]);
    }

    public function test_restore_equipment(): void
    {
        $customer = Customer::factory()->create();
        $equip = CustomerEquipment::factory()->create([
            'cust_id' => $customer->cust_id,
        ]);
        $equip->delete();

        $testObj = new CustomerEquipmentService;
        $testObj->restoreEquipment($equip);

        $this->assertDatabaseHas('customer_equipment', [
            'cust_equip_id' => $equip->cust_equip_id,
            'deleted_at' => null,
        ]);
    }
}
