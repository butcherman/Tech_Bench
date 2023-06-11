<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\FileUploads;
use Tests\TestCase;

class CustomerFileUnitTest extends TestCase
{
    protected $file;

    public function setUp(): void
    {
        parent::setUp();

        $this->file = CustomerFile::factory()->create();
    }

    /**
     * Test Additional Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('uploaded_by', $this->file->toArray());
        $this->assertArrayHasKey('file_type', $this->file->toArray());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $owner = Customer::where('cust_id', $this->file->cust_id)->first();
        $this->assertEquals($this->file->Customer->name, $owner->name);

        $file = FileUploads::where('file_id', $this->file->file_id)->first();
        $this->assertEquals($this->file->FileUpload->file_id, $file->file_id);
    }

    /**
     * Test Soft Deleted Items
     */
    public function test_soft_deleted_models()
    {
        $customer = Customer::where('cust_id', $this->file->cust_id)->first();
        $this->file->delete();

        $deletedItems = (new CustomerFile)->getTrashed($customer);

        $this->assertEquals($deletedItems->toArray(), [
            [
                'item_id' => $this->file->cont_id,
                'item_name' => $this->file->name,
                'item_deleted' => $this->file->deleted_at->toFormattedDateString(),
            ],
        ]);
    }
}
