<?php

namespace Tests\Unit\Services\Customer;

use App\Exceptions\Database\RecordInUseException;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Services\Customer\CustomerFileTypeService;
use Tests\TestCase;

class CustomerFileTypeUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createFileType()
    |---------------------------------------------------------------------------
    */
    public function test_create_file_type(): void
    {
        $data = [
            'description' => 'New File Type',
        ];

        $testObj = new CustomerFileTypeService;
        $res = $testObj->createFileType(collect($data));

        $this->assertEquals($res->description, $data['description']);

        $this->assertDatabaseHas('customer_file_types', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | updateFileType()
    |---------------------------------------------------------------------------
    */
    public function test_update_file_type(): void
    {
        $type = CustomerFileType::create(['description' => 'New File Type']);
        $data = [
            'description' => 'Updated File Type',
        ];

        $testObj = new CustomerFileTypeService;
        $res = $testObj->updateFileType(collect($data), $type);

        $this->assertEquals($res->description, $data['description']);

        $this->assertDatabaseHas('customer_file_types', [
            'file_type_id' => $type->file_type_id,
            'description' => $data['description'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyFileType()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_file_type(): void
    {
        $type = CustomerFileType::create(['description' => 'testing']);

        $testObj = new CustomerFileTypeService;
        $testObj->destroyFileType($type);

        $this->assertDatabaseMissing('customer_file_types', $type->toArray());
    }

    public function test_destroy_file_type_in_use(): void
    {
        $type = CustomerFileType::create(['description' => 'testing']);
        CustomerFile::factory()->create(['file_type_id' => $type->file_type_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new CustomerFileTypeService;
        $testObj->destroyFileType($type);

        $this->assertDatabaseHas('customer_file_types', $type->toArray());
    }
}