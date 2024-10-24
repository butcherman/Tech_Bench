<?php

namespace Tests\Feature\_Jobs\Customer;

use App\Jobs\Customer\UpdateCustomerDataFieldsJob;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Service\Customer\CustomerEquipmentDataService;
use Tests\TestCase;

class UpdateCustomerDataFieldsTest extends TestCase
{
    public function test_job_add_field()
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

        $jobObj = new UpdateCustomerDataFieldsJob($equip);
        $jobObj->handle(new CustomerEquipmentDataService);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $custList[0]->CustomerEquipment[0]->cust_equip_id,
            'field_id' => $dataFieldIds[2],
        ]);
    }

    public function test_job_remove_field()
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

        $jobObj = new UpdateCustomerDataFieldsJob($equip);
        $jobObj->handle(new CustomerEquipmentDataService);

        $this->assertDatabaseMissing('customer_equipment_data', [
            'cust_equip_id' => $custList[0]->CustomerEquipment[0]->cust_equip_id,
            'field_id' => $dataFieldIds[2],
        ]);
    }
}
