<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\CustomerSite;
use App\Models\FileUpload;
use App\Models\User;
use App\Services\Customer\CustomerFileService;
use Tests\TestCase;

class CustomerFileServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createCustomerFile()
    |---------------------------------------------------------------------------
    */
    public function test_create_customer_file()
    {
        $customer = Customer::factory()->create();
        $file = FileUpload::factory()->create();
        $user = User::factory()->create();
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->createCustomerFile(
            collect($data),
            $file,
            $customer,
            $user
        );

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->file_type_id, $data['file_type_id']);
        $this->assertEquals($res->cust_equip_id, $data['cust_equip_id']);

        $this->assertDatabaseHas('customer_files', [
            'name' => $data['name'],
            'file_type_id' => $data['file_type_id'],
            'cust_equip_id' => $data['cust_equip_id'],
        ]);
    }

    public function test_create_customer_equipment_file()
    {
        $customer = Customer::factory()->create();
        $file = FileUpload::factory()->create();
        $user = User::factory()->create();
        $equip = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id])
            ->cust_equip_id;
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'equipment',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => $equip,
            'site_list' => [],
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->createCustomerFile(
            collect($data),
            $file,
            $customer,
            $user
        );

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->file_type_id, $data['file_type_id']);
        $this->assertEquals($res->cust_equip_id, $data['cust_equip_id']);

        $this->assertDatabaseHas('customer_files', [
            'name' => $data['name'],
            'file_type_id' => $data['file_type_id'],
            'cust_equip_id' => $data['cust_equip_id'],
        ]);
    }

    public function test_create_customer_site_file()
    {
        $customer = Customer::factory()->create();
        $file = FileUpload::factory()->create();
        $user = User::factory()->create();
        $sites = CustomerSite::factory()
            ->count(3)
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'site',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => $sites->pluck('cust_site_id')->toArray(),
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->createCustomerFile(
            collect($data),
            $file,
            $customer,
            $user
        );

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->file_type_id, $data['file_type_id']);
        $this->assertEquals($res->cust_equip_id, $data['cust_equip_id']);

        $this->assertDatabaseHas('customer_files', [
            'name' => $data['name'],
            'file_type_id' => $data['file_type_id'],
            'cust_equip_id' => $data['cust_equip_id'],
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $sites[0]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $sites[1]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $sites[2]->cust_site_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateCustomerFile()
    |---------------------------------------------------------------------------
    */
    public function test_update_customer_file()
    {
        $customer = Customer::factory()->create();
        $file = CustomerFile::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->updateCustomerFile(
            collect($data),
            $file,
        );

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->file_type_id, $data['file_type_id']);
        $this->assertEquals($res->cust_equip_id, $data['cust_equip_id']);

        $this->assertDatabaseHas('customer_files', [
            'name' => $data['name'],
            'file_type_id' => $data['file_type_id'],
            'cust_equip_id' => $data['cust_equip_id'],
        ]);
    }

    public function test_update_customer_equipment_file()
    {
        $customer = Customer::factory()->create();
        $file = CustomerFile::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $equip = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id])
            ->cust_equip_id;
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'equipment',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => $equip,
            'site_list' => [],
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->updateCustomerFile(
            collect($data),
            $file,
        );

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->file_type_id, $data['file_type_id']);
        $this->assertEquals($res->cust_equip_id, $data['cust_equip_id']);

        $this->assertDatabaseHas('customer_files', [
            'name' => $data['name'],
            'file_type_id' => $data['file_type_id'],
            'cust_equip_id' => $data['cust_equip_id'],
        ]);
    }

    public function test_update_customer_site_file()
    {
        $customer = Customer::factory()->create();
        $file = CustomerFile::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $sites = CustomerSite::factory()
            ->count(3)
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'site',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => $sites->pluck('cust_site_id')->toArray(),
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->updateCustomerFile(
            collect($data),
            $file,
        );

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->file_type_id, $data['file_type_id']);
        $this->assertEquals($res->cust_equip_id, $data['cust_equip_id']);

        $this->assertDatabaseHas('customer_files', [
            'name' => $data['name'],
            'file_type_id' => $data['file_type_id'],
            'cust_equip_id' => $data['cust_equip_id'],
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $sites[0]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $sites[1]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $sites[2]->cust_site_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCustomerFile()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_customer_file()
    {
        $file = CustomerFile::factory()->create();

        $testObj = new CustomerFileService;
        $testObj->destroyCustomerFile($file);

        $this->assertSoftDeleted(
            'customer_files',
            $file->only(['cust_file_id', 'file_id', 'file_type_id', 'name'])
        );
    }

    public function test_destroy_customer_file_force()
    {
        $file = CustomerFile::factory()->create();

        $testObj = new CustomerFileService;
        $testObj->destroyCustomerFile($file, true);

        $this->assertDatabaseMissing(
            'customer_files',
            $file->only(['cust_file_id', 'file_id', 'file_type_id', 'name'])
        );
    }

    /*
    |---------------------------------------------------------------------------
    | restoreCustomerFile()
    |---------------------------------------------------------------------------
    */
    public function test_restore_customer_file()
    {
        $file = CustomerFile::factory()->create();
        $file->delete();

        $testObj = new CustomerFileService;
        $testObj->restoreCustomerFile($file);

        $this->assertDatabaseHas('customer_files', [
            'name' => $file->name,
            'deleted_at' => null,
        ]);
    }
}
