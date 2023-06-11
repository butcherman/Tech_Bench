<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use Tests\TestCase;

class CustomerContactUnitTest extends TestCase
{
    protected $contact;

    public function setUp(): void
    {
        parent::setUp();

        $this->contact = CustomerContact::factory()->create();
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $newPhone = CustomerContactPhone::factory()->create(['cont_id' => $this->contact->cont_id]);
        $this->assertEquals($this->contact->CustomerContactPhone[0]->only(['cont_id', 'phone_type_id']), $newPhone->only(['cont_id', 'phone_type_id']));

        $owner = Customer::where('cust_id', $this->contact->cust_id)->first();
        $this->assertEquals($this->contact->Customer->name, $owner->name);
    }

    /**
     * Test Soft Deleted Items
     */
    public function test_soft_deleted_models()
    {
        $customer = Customer::where('cust_id', $this->contact->cust_id)->first();
        $this->contact->delete();

        $deletedItems = (new CustomerContact)->getTrashed($customer);

        $this->assertEquals($deletedItems->toArray(), [
            [
                'item_id' => $this->contact->cont_id,
                'item_name' => $this->contact->name,
                'item_deleted' => $this->contact->deleted_at->toFormattedDateString(),
            ],
        ]);
    }
}
