<?php

namespace Tests\_Jobs\Customer;

use App\Jobs\Customer\CreateCustomerDataFieldsJob;
use App\Models\CustomerEquipment;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Service\Customer\CustomerEquipmentDataService;
use Tests\TestCase;

class CreateCustomerDataFieldsTest extends TestCase
{
    public function test_job()
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

        $customerEquip = CustomerEquipment::factory()->for($equip)->createQuietly();

        $jobObj = new CreateCustomerDataFieldsJob($customerEquip);
        $jobObj->handle(new CustomerEquipmentDataService);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $customerEquip->cust_equip_id,
            'field_id' => $dataFieldIds[0],
        ]);

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $customerEquip->cust_equip_id,
            'field_id' => $dataFieldIds[1],
        ]);
    }
}
