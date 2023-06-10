<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerNote;
use Tests\TestCase;

class CustomerNoteUnitTest extends TestCase
{
    protected $note;

    public function setUp():void
    {
        parent::setup();

        $this->note = CustomerNote::factory()->create();
    }

    /**
     * Test Model Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('author', $this->note->toArray());
        $this->assertArrayHasKey('updated_author', $this->note->toArray());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $owner = Customer::where('cust_id', $this->note->cust_id)->first();
        $this->assertEquals($this->note->Customer->name, $owner->name);
    }

    /**
     * Test Soft Deleted Items
     */
    public function test_soft_deleted_models()
    {
        $customer = Customer::where('cust_id', $this->note->cust_id)->first();
        $this->note->delete();

        $deletedItems = (new CustomerNote())->getTrashed($customer);

        $this->assertEquals($deletedItems->toArray(), [
            [
                'item_id' => $this->note->note_id,
                'item_name' => $this->note->subject,
                'item_deleted' => $this->note->deleted_at->toFormattedDateString(),
            ]
        ]);
    }
}
