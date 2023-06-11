<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use Tests\TestCase;

class CustomerUnitTest extends TestCase
{
    protected $customer;

    public function setUp(): void
    {
        parent::setup();

        $this->customer = Customer::factory()->create();
    }

    /**
     * Test Route/Model Binding with both cust_id and slug
     */
    public function test_route_model_binding()
    {
        $custById = (new Customer)->resolveRouteBinding($this->customer->cust_id);
        $this->assertEquals($custById->only(['cust_id', 'name', 'address']), $this->customer->only(['cust_id', 'name', 'address']));

        $custByName = (new Customer)->resolveRouteBinding($this->customer->slug);
        $this->assertEquals($custByName->only(['cust_id', 'name', 'address']), $this->customer->only(['cust_id', 'name', 'address']));
    }

    /**
     * Test Additional Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('child_count', $this->customer->toArray());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $equip = CustomerEquipment::factory()->create(['cust_id' => $this->customer->cust_id]);
        $cont = CustomerContact::factory()->create(['cust_id' => $this->customer->cust_id]);
        $note = CustomerNote::factory()->create(['cust_id' => $this->customer->cust_id]);
        $file = CustomerFile::factory()->create(['cust_id' => $this->customer->cust_id]);

        $this->assertEquals($this->customer->CustomerEquipment[0]->only(['cust_equip_id', 'equip_id']), $equip->only(['cust_equip_id', 'equip_id']));
        $this->assertEquals($this->customer->CustomerContact[0]->only(['cont_id', 'name']), $cont->only(['cont_id', 'name']));
        $this->assertEquals($this->customer->CustomerNote[0]->only(['note_id', 'subject']), $note->only(['note_id', 'subject']));
        $this->assertEquals($this->customer->CustomerFile[0]->only(['file_id', 'file_type_id']), $file->only(['file_id', 'file_type_id']));
    }

    /**
     * Test Model Parent Relationships
     */
    public function test_model_parent_relationships()
    {
        $parent = Customer::factory()->create();
        $equip = CustomerEquipment::factory()->create(['cust_id' => $parent->cust_id, 'shared' => true]);
        $cont = CustomerContact::factory()->create(['cust_id' => $parent->cust_id, 'shared' => true]);
        $note = CustomerNote::factory()->create(['cust_id' => $parent->cust_id, 'shared' => true]);
        $file = CustomerFile::factory()->create(['cust_id' => $parent->cust_id, 'shared' => true]);

        $this->customer->update(['parent_id' => $parent->cust_id]);

        $this->assertEquals($this->customer->ParentEquipment[0]->only(['cust_equip_id', 'equip_id']), $equip->only(['cust_equip_id', 'equip_id']));
        $this->assertEquals($this->customer->ParentContact[0]->only(['cont_id', 'name']), $cont->only(['cont_id', 'name']));
        $this->assertEquals($this->customer->ParentNote[0]->only(['note_id', 'subject']), $note->only(['note_id', 'subject']));
        $this->assertEquals($this->customer->ParentFile[0]->only(['file_id', 'file_type_id']), $file->only(['file_id', 'file_type_id']));
    }
}
