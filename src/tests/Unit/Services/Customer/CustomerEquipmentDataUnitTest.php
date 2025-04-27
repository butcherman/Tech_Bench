<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Services\Customer\CustomerEquipmentDataService;
use Tests\TestCase;

class CustomerEquipmentDataUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createEquipmentDataFields()
    |---------------------------------------------------------------------------
    */
    public function test_create_equipment_data_fields(): void
    {
        $fields = DataFieldType::factory()->count(2)->createQuietly();
        $equip = EquipmentType::factory()->create();

        $equip->DataFieldType()->attach([
            $fields[0]->type_id => ['order' => 0],
            $fields[1]->type_id => ['order' => 1],
        ]);

        $dataFieldIds = DataField::where('equip_id', $equip->equip_id)
            ->get()
            ->pluck('field_id')
            ->toArray();

        $customerEquip = CustomerEquipment::factory()
            ->for($equip)
            ->createQuietly();

        $testObj = new CustomerEquipmentDataService;
        $testObj->createCustomerEquipmentDataFields($customerEquip);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $customerEquip->cust_equip_id,
            'field_id' => $dataFieldIds[0],
        ]);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $customerEquip->cust_equip_id,
            'field_id' => $dataFieldIds[1],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateEquipmentDataFieldsForEquipment()
    |---------------------------------------------------------------------------
    */
    public function test_update_equipment_data_fields(): void
    {
        $fields = DataFieldType::factory()->count(3)->createQuietly();
        $equip = EquipmentType::factory()->create();

        $equip->DataFieldType()->attach([
            $fields[0]->type_id => ['order' => 0],
            $fields[1]->type_id => ['order' => 1],
        ]);

        $dataFieldIds = DataField::where('equip_id', $equip->equip_id)
            ->get()
            ->pluck('field_id')
            ->toArray();

        $custList = Customer::factory()->count(3)->create();
        foreach ($custList as $cust) {
            $newEquip = CustomerEquipment::create([
                'cust_id' => $cust->cust_id,
                'equip_id' => $equip->equip_id,
            ]);

            CustomerEquipmentData::create([
                'cust_equip_id' => $newEquip->cust_equip_id,
                'field_id' => $dataFieldIds[0],
            ]);

            CustomerEquipmentData::create([
                'cust_equip_id' => $newEquip->cust_equip_id,
                'field_id' => $dataFieldIds[1],
            ]);
        }

        // Attach a new field
        $equip->DataFieldType()->attach([
            $fields[2]->type_id => ['order' => 2],
        ]);

        // Refetch the ID List
        $dataFieldIds = DataField::where('equip_id', $equip->equip_id)
            ->get()
            ->pluck('field_id')
            ->toArray();

        $this->assertDatabaseMissing('customer_equipment_data', [
            'cust_equip_id' => $custList[0]->Equipment[0]->cust_equip_id,
            'field_id' => $dataFieldIds[2],
        ]);

        $testObj = new CustomerEquipmentDataService;
        $testObj->updateEquipmentDataFieldsForEquipment($equip);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $custList[0]->Equipment[0]->cust_equip_id,
            'field_id' => $dataFieldIds[2],
        ]);
    }
}
