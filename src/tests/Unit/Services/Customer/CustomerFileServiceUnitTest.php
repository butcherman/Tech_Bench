<?php

namespace Tests\Unit\Services\Customer;

use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\CustomerSite;
use App\Models\FileUpload;
use App\Models\User;
use App\Services\Customer\CustomerFileService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CustomerFileServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createCustomerFile()
    |---------------------------------------------------------------------------
    */
    public function test_create_customer_file(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $fileUpload = FileUpload::factory()->create();
        $data = [
            'name' => 'Some Name',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->createCustomerFile(collect($data), $fileUpload, $customer, $user);

        $this->assertEquals($res->name, $data['name']);

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'user_id' => $user->user_id,
            'name' => $data['name'],
            'file_id' => $fileUpload->file_id,
        ]);
    }

    public function test_create_customer_file_site_file(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3), 'sites')
            ->create();
        $fileUpload = FileUpload::factory()->create();
        $data = [
            'name' => 'Some Name',
            'file_type' => 'site',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => $customer->Sites
                ->pluck('cust_site_id')
                ->toArray(),
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->createCustomerFile(collect($data), $fileUpload, $customer, $user);

        $this->assertEquals($res->name, $data['name']);

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'user_id' => $user->user_id,
            'name' => $data['name'],
            'file_id' => $fileUpload->file_id,
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $customer->Sites[0]->cust_site_id,
            'cust_file_id' => $res->cust_file_id,
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $customer->Sites[1]->cust_site_id,
            'cust_file_id' => $res->cust_file_id,
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $customer->Sites[2]->cust_site_id,
            'cust_file_id' => $res->cust_file_id,
        ]);
    }

    public function test_create_customer_file_equipment_file(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()
            ->has(CustomerEquipment::factory(), 'equipment')
            ->create();
        $fileUpload = FileUpload::factory()->create();
        $data = [
            'name' => 'Some Name',
            'file_type' => 'equipment',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => $customer->Equipment[0]->cust_equip_id,
            'site_list' => [],
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->createCustomerFile(collect($data), $fileUpload, $customer, $user);

        $this->assertEquals($res->name, $data['name']);

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'user_id' => $user->user_id,
            'name' => $data['name'],
            'file_id' => $fileUpload->file_id,
            'cust_equip_id' => $data['cust_equip_id'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateCustomerFile()
    |---------------------------------------------------------------------------
    */
    public function test_update_customer_file(): void
    {
        $customer = Customer::factory()->create();
        $file = CustomerFile::factory()->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'Some Name',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->updateCustomerFile(collect($data), $file);

        $this->assertEquals($res->name, $data['name']);

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'name' => $data['name'],
            'file_type_id' => $data['file_type_id'],
        ]);
    }

    public function test_update_customer_file_site_file(): void
    {
        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3), 'sites')
            ->create();
        $file = CustomerFile::factory()->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'Some Name',
            'file_type' => 'site',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => $customer->Sites
                ->pluck('cust_site_id')
                ->toArray(),
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->updateCustomerFile(collect($data), $file);

        $this->assertEquals($res->name, $data['name']);

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'name' => $data['name'],
            'file_type_id' => $data['file_type_id'],
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $customer->Sites[0]->cust_site_id,
            'cust_file_id' => $res->cust_file_id,
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $customer->Sites[1]->cust_site_id,
            'cust_file_id' => $res->cust_file_id,
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $customer->Sites[2]->cust_site_id,
            'cust_file_id' => $res->cust_file_id,
        ]);
    }

    public function test_update_customer_file_equipment_file(): void
    {
        $customer = Customer::factory()
            ->has(CustomerEquipment::factory(), 'equipment')
            ->create();
        $file = CustomerFile::factory()->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'Some Name',
            'file_type' => 'equipment',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => $customer->Equipment[0]->cust_equip_id,
            'site_list' => [],
        ];

        $testObj = new CustomerFileService;
        $res = $testObj->updateCustomerFile(collect($data), $file);

        $this->assertEquals($res->name, $data['name']);

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'name' => $data['name'],
            'file_type_id' => $data['file_type_id'],
            'cust_equip_id' => $data['cust_equip_id'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCustomerFile()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_customer_file(): void
    {
        $file = CustomerFile::factory()->create();

        $testObj = new CustomerFileService;
        $testObj->destroyCustomerFile($file);

        $this->assertSoftDeleted('customer_files', [
            'cust_file_id' => $file->cust_file_id,
        ]);
    }

    public function test_destroy_customer_file_force(): void
    {
        Event::fake(FileDataDeletedEvent::class);

        $file = CustomerFile::factory()->create();

        $testObj = new CustomerFileService;
        $testObj->destroyCustomerFile($file, true);

        $this->assertDatabaseMissing('customer_files', [
            'cust_file_id' => $file->cust_file_id,
        ]);

        Event::assertDispatched(FileDataDeletedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | restoreCustomerFile()
    |---------------------------------------------------------------------------
    */
    public function test_restore_customer_file(): void
    {
        $file = CustomerFile::factory()->create();
        $file->delete();

        $testObj = new CustomerFileService;
        $testObj->restoreCustomerFile($file);

        $this->assertDatabaseHas('customer_files', [
            'cust_file_id' => $file->cust_file_id,
            'deleted_at' => null,
        ]);
    }
}
