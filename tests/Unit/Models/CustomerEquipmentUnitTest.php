<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use Tests\TestCase;

class CustomerEquipmentUnitTest extends TestCase
{
    protected $equipment;

    public function setup(): void
    {
        parent::setup();

        $this->equipment = CustomerEquipment::factory()->create();
    }

    /**
     * Test Additional Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('name', $this->equipment->toArray());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $owner = Customer::where('cust_id', $this->equipment->cust_id)->first();
        $this->assertEquals($this->equipment->Customer->name, $owner->name);
    }

    /**
     * Test Soft Deleted Items
     */
    public function test_soft_deleted_models()
    {
        $customer = Customer::where('cust_id', $this->equipment->cust_id)->first();
        $this->equipment->delete();

        $deletedItems = (new CustomerEquipment)->getTrashed($customer);

        $this->assertEquals($deletedItems->toArray(), [
            [
                'item_id' => $this->equipment->equip_id,
                'item_name' => $this->equipment->name,
                'item_deleted' => $this->equipment->deleted_at->toFormattedDateString(),
            ],
        ]);
    }
}
