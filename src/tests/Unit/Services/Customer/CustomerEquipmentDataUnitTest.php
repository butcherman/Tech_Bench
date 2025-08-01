<?php

namespace Tests\Unit\Services\Customer;

use App\Exceptions\Customer\CustomerEquipmentDataFailedVerificationException;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Services\Customer\CustomerEquipmentDataService;
use Illuminate\Support\Facades\Log;
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

    /*
    |---------------------------------------------------------------------------
    | updateDataFieldValue()
    |---------------------------------------------------------------------------
    */
    public function test_update_data_field_value(): void
    {
        $equipment = CustomerEquipment::factory()->create();
        $fields = DataField::factory()
            ->count(4)
            ->create(['equip_id' => $equipment->equip_id]);

        $data1 = CustomerEquipmentData::create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[0]->field_id,
            'value' => '10.1.25.12',
        ]);
        $data2 = CustomerEquipmentData::create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[1]->field_id,
            'value' => $value1 = '3.5',
        ]);
        $data3 = CustomerEquipmentData::create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[2]->field_id,
            'value' => $value2 = 'admin',
        ]);
        $data4 = CustomerEquipmentData::create([
            'cust_equip_id' => $equipment->cust_equip_id,
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
        $testObj->updateDataFieldValue(collect($data), $equipment);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[0]->field_id,
            'value' => $newVal0,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[1]->field_id,
            'value' => $newVal1,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[2]->field_id,
            'value' => $newVal2,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[3]->field_id,
            'value' => $newVal3,
        ]);
    }

    public function test_update_data_field_value_invalid_customer(): void
    {
        $invalid = CustomerEquipment::factory()->create();
        $equipment = CustomerEquipment::factory()->create();
        $fields = DataField::factory()
            ->count(4)
            ->create(['equip_id' => $equipment->equip_id]);

        $data1 = CustomerEquipmentData::create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[0]->field_id,
            'value' => '10.1.25.12',
        ]);
        $data2 = CustomerEquipmentData::create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[1]->field_id,
            'value' => $value1 = '3.5',
        ]);
        $data3 = CustomerEquipmentData::create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'field_id' => $fields[2]->field_id,
            'value' => $value2 = 'admin',
        ]);
        $data4 = CustomerEquipmentData::create([
            'cust_equip_id' => $equipment->cust_equip_id,
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

        $this->expectException(CustomerEquipmentDataFailedVerificationException::class);

        $testObj = new CustomerEquipmentDataService;
        $testObj->updateDataFieldValue(collect($data), $invalid);
    }

    public function test_update_field_data_do_not_log_value(): void
    {
        $testType = DataFieldType::where('name', 'Login Password')->first();
        $testType->do_not_log_value = true;
        $testType->save();

        $dataField = DataField::factory()
            ->create(['type_id' => $testType->type_id]);
        $customerField = CustomerEquipmentData::factory()
            ->create(['field_id' => $dataField->field_id]);

        Log::shouldReceive('info')
            ->once()
            ->with('Customer Equipment Data Updated by Internal Service', [
                'customer_equipment_id' => $customerField->cust_equip_id,
                'data-field' => $customerField->field_name,
                'old-value' => '<REDACTED>',
                'new-value' => '<REDACTED>',
            ]);

        $customerField->value = 'password';
        $customerField->save();
    }

    /*
    |---------------------------------------------------------------------------
    | checkAllCustomerEquipment()
    |---------------------------------------------------------------------------
    */
    public function test_check_all_customer_equipment_no_fix(): void
    {
        $equip = EquipmentType::factory()->create();
        $equipList = CustomerEquipment::factory()
            ->count(10)
            ->create(['equip_id' => $equip->equip_id]);

        DataField::create([
            'equip_id' => $equip->equip_id,
            'type_id' => 1,
            'order' => 0,
        ]);
        DataField::create([
            'equip_id' => $equip->equip_id,
            'type_id' => 2,
            'order' => 1,
        ]);

        $testObj = new CustomerEquipmentDataService;
        $res = $testObj->checkAllCustomerEquipment(false);

        foreach ($res as $r) {
            $this->assertCount(2, $r);
        }

        foreach ($equipList as $custEquip) {
            $this->assertDatabaseMissing('customer_equipment_data', [
                'cust_equip_id' => $custEquip->cust_equip_id,
                'field_id' => 1,
            ]);
            $this->assertDatabaseMissing('customer_equipment_data', [
                'cust_equip_id' => $custEquip->cust_equip_id,
                'field_id' => 2,
            ]);
        }
    }

    public function test_check_all_customer_equipment_with_fix(): void
    {
        $equip = EquipmentType::factory()->create();
        $equipList = CustomerEquipment::factory()
            ->count(10)
            ->create(['equip_id' => $equip->equip_id]);

        $field1 = DataField::create([
            'equip_id' => $equip->equip_id,
            'type_id' => 1,
            'order' => 0,
        ]);
        $field2 = DataField::create([
            'equip_id' => $equip->equip_id,
            'type_id' => 2,
            'order' => 1,
        ]);

        $testObj = new CustomerEquipmentDataService;
        $res = $testObj->checkAllCustomerEquipment(true);

        foreach ($res as $r) {
            $this->assertCount(2, $r);
        }

        foreach ($equipList as $custEquip) {
            $this->assertDatabaseHas('customer_equipment_data', [
                'cust_equip_id' => $custEquip->cust_equip_id,
                'field_id' => $field1->field_id,
            ]);
            $this->assertDatabaseHas('customer_equipment_data', [
                'cust_equip_id' => $custEquip->cust_equip_id,
                'field_id' => $field2->field_id,
            ]);
        }
    }
}
