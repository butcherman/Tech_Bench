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
        $testObj->createEquipmentDataFields($customerEquip);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $customerEquip->cust_equip_id,
            'field_id' => $dataFieldIds[0],
        ]);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $customerEquip->cust_equip_id,
            'field_id' => $dataFieldIds[1],
        ]);
    }

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
            'cust_equip_id' => $custList[0]->CustomerEquipment[0]->cust_equip_id,
            'field_id' => $dataFieldIds[2],
        ]);

        $testObj = new CustomerEquipmentDataService;
        $testObj->updateEquipmentDataFields($equip);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $custList[0]->CustomerEquipment[0]->cust_equip_id,
            'field_id' => $dataFieldIds[2],
        ]);
    }

    public function test_update_equipment_data_fields_remove_field(): void
    {
        $fields = DataFieldType::factory()->count(3)->createQuietly();
        $equip = EquipmentType::factory()->create();

        $equip->DataFieldType()->attach([
            $fields[0]->type_id => ['order' => 0],
            $fields[1]->type_id => ['order' => 1],
            $fields[2]->type_id => ['order' => 2],
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

            CustomerEquipmentData::create([
                'cust_equip_id' => $newEquip->cust_equip_id,
                'field_id' => $dataFieldIds[2],
            ]);
        }

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $custList[0]->CustomerEquipment[0]->cust_equip_id,
            'field_id' => $dataFieldIds[2],
        ]);

        // Remove a field
        $equip->DataFieldType()->detach($fields[2]->type_id);

        $testObj = new CustomerEquipmentDataService;
        $testObj->updateEquipmentDataFields($equip);

        $this->assertDatabaseMissing('customer_equipment_data', [
            'cust_equip_id' => $custList[0]->CustomerEquipment[0]->cust_equip_id,
            'field_id' => $dataFieldIds[2],
        ]);
    }

    public function test_update_data_field_value(): void
    {
        $cust = Customer::factory()->create();
        $equip = CustomerEquipment::factory()
            ->create(['cust_id' => $cust->cust_id]);
        $fields = DataField::factory()
            ->count(4)
            ->create(['equip_id' => $equip->equip_id]);

        $data1 = CustomerEquipmentData::create([
            'cust_equip_id' => $equip->cust_equip_id,
            'field_id' => $fields[0]->field_id,
            'value' => '10.1.25.12',
        ]);
        $data2 = CustomerEquipmentData::create([
            'cust_equip_id' => $equip->cust_equip_id,
            'field_id' => $fields[1]->field_id,
            'value' => $value1 = '3.5',
        ]);
        $data3 = CustomerEquipmentData::create([
            'cust_equip_id' => $equip->cust_equip_id,
            'field_id' => $fields[2]->field_id,
            'value' => $value2 = 'admin',
        ]);
        $data4 = CustomerEquipmentData::create([
            'cust_equip_id' => $equip->cust_equip_id,
            'field_id' => $fields[3]->field_id,
            'value' => $value3 = 'password',
        ]);

        $data = [
            'saveData' => [
                [
                    'fieldId' => $data1->id,
                    'value' => $newVal0 = '12.52.25.1',
                ],
                [
                    'fieldId' => $data2->id,
                    'value' => $newVal1 = '8.6',
                ],
                [
                    'fieldId' => $data3->id,
                    'value' => $newVal2 = 'billy_bob',
                ],
                [
                    'fieldId' => $data4->id,
                    'value' => $newVal3 = 'newPassword',
                ],
            ],
        ];

        $testObj = new CustomerEquipmentDataService;
        $testObj->updateDataFieldValue(collect($data));

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $equip->cust_equip_id,
            'field_id' => $fields[0]->field_id,
            'value' => $newVal0,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $equip->cust_equip_id,
            'field_id' => $fields[1]->field_id,
            'value' => $newVal1,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $equip->cust_equip_id,
            'field_id' => $fields[2]->field_id,
            'value' => $newVal2,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $equip->cust_equip_id,
            'field_id' => $fields[3]->field_id,
            'value' => $newVal3,
        ]);
    }
}