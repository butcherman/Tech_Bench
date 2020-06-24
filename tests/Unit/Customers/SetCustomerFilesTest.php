<?php

namespace Tests\Unit\Customers;

use App\CustomerFiles;
use App\Customers;
use App\Domains\Customers\setCustomerFiles;
use App\Http\Requests\Customers\EditFileRequest;
use App\Http\Requests\Customers\NewFileRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SetCustomerFilesTest extends TestCase
{
    public function test_update_file()
    {
        $cust = factory(Customers::class)->create();
        $file = factory(CustomerFiles::class)->create(['cust_id' => $cust->cust_id]);
        $data = new EditFileRequest([
            'cust_id'      => $cust->cust_id,
            'file_type_id' => 1,
            'shared'       => false,
            'name'         => 'New File Name',
        ]);

        $res = (new setCustomerFiles)->updateFile($data, $file->cust_file_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_files', ['cust_file_id' => $file->cust_file_id, 'cust_id' => $cust->cust_id, 'shared' => 0, 'name' => 'New File Name']);
    }

    public function test_update_file_to_parent()
    {
        $parent = factory(Customers::class)->create();
        $cust   = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $file   = factory(CustomerFiles::class)->create(['cust_id' => $cust->cust_id]);
        $data   = new EditFileRequest([
            'cust_id'      => $cust->cust_id,
            'file_type_id' => 1,
            'shared'       => true,
            'name'         => 'New File Name',
        ]);

        $res = (new setCustomerFiles)->updateFile($data, $file->cust_file_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_files', ['cust_file_id' => $file->cust_file_id, 'cust_id' => $parent->cust_id, 'shared' => 1, 'name' => 'New File Name']);
    }

    public function test_delete_cust_file()
    {
        $cust = factory(Customers::class)->create();
        $file = factory(CustomerFiles::class)->create(['cust_id' => $cust->cust_id]);

        $res = (new setCustomerFiles)->deleteCustFile($file->cust_file_id);
        $this->assertTrue($res);
        $this->assertDatabaseMissing('customer_files', $file->toArray());
    }
}
