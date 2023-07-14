<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\DataField;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\TechTipEquipment;
use Tests\TestCase;

class EquipmentTypeUnitTest extends TestCase
{
    protected $equip;

    public function setUp(): void
    {
        parent::setUp();

        $this->equip = EquipmentType::factory()->create();
    }

    /**
     * Test Route Model Binding
     */
    public function test_route_model_binding()
    {
        $this->assertEquals('equip_id', $this->equip->getRouteKeyName());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $owner = EquipmentCategory::where('cat_id', $this->equip->cat_id)->first();
        $this->assertEquals($this->equip->EquipmentCategory->name, $owner->name);

        DataField::factory()->create([
            'equip_id' => $this->equip->equip_id,
            'type_id' => 1,
            'order' => 1,
        ]);

        $this->assertEquals($this->equip->DataFieldType[0]->name, 'IP Address');
    }

    public function test_customer_model_relationship()
    {
        $customer = Customer::factory()->create();
        CustomerEquipment::create([
            'cust_id' => $customer->cust_id,
            'equip_id' => $this->equip->equip_id,
            'shared' => false,
        ]);

        $this->assertEquals($customer->only(['cust_id', 'name', 'address']), $this->equip->Customer[0]->only(['cust_id', 'name', 'address']));
    }

    public function test_tech_tip_model_relationship()
    {
        $tip = TechTip::factory()->create();
        TechTipEquipment::create([
            'tip_id' => $tip->tip_id,
            'equip_id' => $this->equip->equip_id,
        ]);

        $this->assertEquals($tip->only(['tip_id', 'subject']), $this->equip->TechTip[0]->only(['tip_id', 'subject']));
    }
}
